<?php

namespace Relax\Loader\FileFinder;

use Relax\Loader\Contracts\FileLoader as FileLoaderInterface;
use Relax\Loader\Contracts\Filesystem;
use Relax\Loader\Contracts\FileFinder;
use Relax\Loader\Filesystem\FileNotFoundException;

class FileLoader implements FileLoaderInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FileFinder
     */
    private $fileFinder;

    /**
     * Create a new FileLoader instance.
     *
     * @param  Filesystem $filesystem
     * @param FileFinder $fileFinder
     */
    public function __construct(Filesystem $filesystem, FileFinder $fileFinder)
    {
        $this->filesystem = $filesystem;
        $this->fileFinder = $fileFinder;
    }

    /**
     * Load a file's contents by requiring it.
     *
     * @param  string $name The file name to load (without extension)
     * @return mixed
     * @throws FileNotFoundException If the file could not be found
     */
    public function loadRequire($name)
    {
        // Get file name (or throws FileNotFoundException)
        $fileName = $this->fileFinder->find($name);

        /* @noinspection PhpIncludeInspection */
        return require $fileName;
    }

    /**
     * Reads a file into a string.
     *
     * @param  string $name The file name to load (without extension)
     * @return string
     * @throws FileNotFoundException If the file could not be found
     */
    public function load($name)
    {
        // Get file name (or throws FileNotFoundException)
        $fileName = $this->fileFinder->find($name);

        return $this->filesystem->get($fileName);
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
        $this->fileFinder->addPaths($paths, $prepend);

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
        $this->fileFinder->addFileExtensions($fileExtensions, $prepend);

        return $this;
    }

    /**
     * Find the given file in the configured paths.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @return string  The fully qualified filename of the file found.
     *
     * @throws \Exception if the file was not found on any of the configured paths.
     */
    public function find($name)
    {
        return $this->fileFinder->find($name);
    }
}
