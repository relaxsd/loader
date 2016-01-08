<?php

namespace Relax\Loader\FileFinder;

use Relax\Loader\Contracts\FileFinder as FileFinderInterface;
use Relax\Loader\Contracts\Filesystem;
use Relax\Loader\Filesystem\FileNotFoundException;

/**
 * Class FileFinder.
 * An implemention of the FileFinder interface that has no namespace support.
 */
class FileFinder implements FileFinderInterface
{
    /**
     * Interface to the filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The array of file paths to search.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * The file extensions to search.
     *
     * @var array
     */
    protected $fileExtensions = [];

    /**
     * A cache of file names that have already been resolved.
     *
     * @var array
     */
    protected $fileNames = [];

    /**
     * Create a new file file loader instance.
     *
     * @param  Filesystem $filesystem
     * @param  string|array $paths
     * @param  string|array $fileExtensions
     */
    public function __construct(Filesystem $filesystem, $paths = [], $fileExtensions = [])
    {
        $this->filesystem = $filesystem;
        $this->addPaths($paths);
        $this->addFileExtensions($fileExtensions);
    }

    /**
     * Add one or more paths to the finder.
     *
     * @param  string|array $paths
     * @param bool $prepend If true, prepends the path(s) before earlier registered paths so it will be searched first.
     *                      If false (default), appends the path(s) to earlier registered paths so it will be searched last.
     * @return $this
     */
    public function addPaths($paths, $prepend = false)
    {
        $paths = is_array($paths) ? $paths : [$paths];

        $this->paths = $prepend
            ? array_merge($paths, $this->paths)
            : array_merge($this->paths, $paths);

        return $this;
    }

    /**
     * Add one or more file extensions to the finder.
     *
     * @param  string|array $fileExtensions
     * @param bool $prepend If true, prepends the file extension(s) before any earlier registered extensions so it will be found first.
     *                      If false (default), appends the file extension(s) to earlier registered extensions so it will be found last.
     * @return $this
     */
    public function addFileExtensions($fileExtensions, $prepend = false)
    {
        $fileExtensions = is_array($fileExtensions) ? $fileExtensions : [$fileExtensions];

        $this->fileExtensions = $prepend
            ? array_merge($fileExtensions, $this->fileExtensions)
            : array_merge($this->fileExtensions, $fileExtensions);

        return $this;
    }

    /**
     * Find the given file in the configured paths.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @return string  The fully qualified filename of the file found.
     *
     * @throws FileNotFoundException if the file was not found on any of the configured paths.
     */
    public function find($name)
    {
        if (isset($this->fileNames[$name])) {
            return $this->fileNames[$name];
        }

        return $this->fileNames[$name] = $this->findInPaths($name, $this->paths);
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
                if ($this->filesystem->exists($filePath = "{$path}/{$file}")) {
                    return $filePath;
                }
            }
        }

        throw new FileNotFoundException("File '{$name}' not found.");
    }

    /**
     * Get an array of possible file names.
     *
     * @param  string $name File name. It may contain multiple parts, like 'en-gb.users'
     * @return array
     */
    protected function getPossibleFileNames($name)
    {
        // Translate 'dot-notation' to directory names
        $name = str_replace('.', '/', $name);

        return array_map(function ($fileExtension) use ($name) {
            return "{$name}.{$fileExtension}";

        }, $this->fileExtensions);
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @return array
     */
    public function getFileExtensions()
    {
        return $this->fileExtensions;
    }
}
