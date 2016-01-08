<?php

namespace Relax\Loader\FileFinder;

use Relax\Loader\Filesystem\FileNotFoundException;

/**
 * Class NamespaceFileFinder.
 * An implemention of the FileFinder with namespace support.
 */
class NamespaceFileFinder extends FileFinder
{
    /**
     * Hint path delimiter value.
     *
     * @var string
     */
    const HINT_PATH_DELIMITER = '::';

    /**
     * Array of search paths for each namespace.
     *
     * @var array
     */
    protected $namespacePaths = [];

    /**
     * Find the given file in the configured paths.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @return string  The fully qualified filename of the file found.
     *
     * @throws FileNotFoundException if the file was not found on any of the configured paths.
     * @throws \InvalidArgumentException if the namespace was wrong or unknown.
     */
    public function find($name)
    {
        if (isset($this->fileNames[$name])) {
            return $this->fileNames[$name];
        }

        $result = self::hasNamespace($name = trim($name))
            ? $this->findInNamespace($name)
            : $this->findInPaths($name, $this->paths);

        // No exceptions, so we found the file. Cache the filename and return it.
        return $this->fileNames[$name] = $result;
    }

    /**
     * Get the path to a file with a named path.
     *
     * @param  string $name
     * @return string
     */
    protected function findInNamespace($name)
    {
        // Split the namespace::name (or throw an exception)
        list($namespace, $name) = $this->getNamespaceSegments($name);

        // No exception, so we have known namespace. Find the file in its paths.
        return $this->findInPaths($name, $this->namespacePaths[$namespace]);
    }

    /**
     * Get the segments of a file with a named path.
     *
     * @param  string $name
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    protected function getNamespaceSegments($name)
    {
        $segments = explode(static::HINT_PATH_DELIMITER, $name);

        if (count($segments) != 2) {
            throw new \InvalidArgumentException("Name '$name' is invalid.");
        }

        if (!$this->namespaceIsKnown($segments[0])) {
            throw new \InvalidArgumentException("Namespace '{$segments[0]}' is not registered.");
        }

        return $segments;
    }

    /**
     * Add namespace paths to the finder (to be found last).
     *
     * @param  string $namespace Namespace name
     * @param  string|array $paths Search paths for this namespace
     * @param bool $prepend If true, adds the path(s) before earlier registered paths.
     * @return $this
     */
    public function addNamespacePaths($namespace, $paths, $prepend = false)
    {
        $paths = is_array($paths) ? $paths : [$paths];

        if ($this->namespaceIsKnown($namespace)) {
            $paths = $prepend
                ? array_merge($paths, $this->namespacePaths[$namespace])
                : array_merge($this->namespacePaths[$namespace], $paths);
        }

        $this->namespacePaths[$namespace] = $paths;

        return $this;
    }

    /**
     * Find the given file in the list of paths.
     * This protected function makes it easy for subclasses to find within namespaces.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @param  array $paths
     * @return string  The full path of the file found.
     *
     * @throws FileNotFoundException if the file was not found on any of the paths.
     */
    protected function findInPaths($name, $paths)
    {
        foreach ((array) $paths as $path) {
            foreach ($this->getPossibleFileNames($name) as $file) {
                if ($this->filesystem->exists($filePath = $path.'/'.$file)) {
                    return $filePath;
                }
            }
        }

        throw new FileNotFoundException("File '{$name}' not found.");
    }

    /**
     * Get an array of possible file names.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @return array
     */
    protected function getPossibleFileNames($name)
    {
        $name = str_replace('.', '/', $name);

        return array_map(function ($fileExtension) use ($name) {
            return $name.'.'.$fileExtension;

        }, $this->fileExtensions);
    }

    /**
     * @param string $namespace The name of the namespace
     * @return bool True if the namespace was registered.
     */
    protected function namespaceIsKnown($namespace)
    {
        return array_key_exists($namespace, $this->namespacePaths);
    }

    /**
     * Checks if the name contains a namespace, eg 'package::name'.
     *
     * @param  string $name
     * @return bool
     */
    private static function hasNamespace($name)
    {
        return strpos($name, static::HINT_PATH_DELIMITER) > 0;
    }
}
