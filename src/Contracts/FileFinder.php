<?php

namespace Relax\Loader\Contracts;

interface FileFinder
{
    /**
     * Add one or more paths to the finder.
     *
     * @param  string|array $paths
     * @param bool $prepend If true, prepends the path(s) before earlier registered paths so it will be searched first.
     *                      If false (default), appends the path(s) to earlier registered paths so it will be searched last.
     * @return $this
     */
    public function addPaths($paths, $prepend = false);

    /**
     * Add one or more file extensions to the finder.
     *
     * @param  string|array $fileExtensions
     * @param bool $prepend If true, prepends the file extension(s) before any earlier registered extensions so it will be found first.
     *                      If false (default), appends the file extension(s) to earlier registered extensions so it will be found last.
     * @return $this
     */
    public function addFileExtensions($fileExtensions, $prepend = false);

    /**
     * Find the given file in the configured paths.
     *
     * @param  string $name File name, may contain multiple parts, like 'en-gb.users'
     * @return string  The fully qualified filename of the file found.
     *
     * @throws \Exception if the file was not found on any of the configured paths.
     */
    public function find($name);
}
