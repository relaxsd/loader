<?php

namespace Relax\Loader\Contracts;

use Relax\Loader\Filesystem\FileAlreadyExistsException;
use Relax\Loader\Filesystem\FileNotFoundException;

interface Filesystem
{
    /**
     * Make a file.
     *
     * @param string $file
     * @param mixed $content
     * @param bool $overwrite
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function make($file, $content, $overwrite = true);

    /**
     * Determines if a file exists.
     *
     * @param string $file
     * @return bool
     */
    public function exists($file);

    /**
     * Reads a file into a string.
     *
     * @param string $file
     * @throws FileNotFoundException
     * @return string
     */
    public function get($file);

    /**
     * Create a directory.
     *
     * @param  string $path
     * @param  int $mode
     * @param  bool $recursive
     * @param  bool $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false);
}
