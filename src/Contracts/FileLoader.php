<?php

namespace Relax\Loader\Contracts;

use Relax\Loader\Filesystem\FileNotFoundException;

/**
 * Interface FileLoader.
 *
 * A FileLoader is a FileFinder that can not only find files,
 * but also load them using a Filesystem or by PHPs require().
 */
interface FileLoader extends FileFinder
{
    /**
     * Load a file's contents by requiring it.
     *
     * @param  string $name The file name to load (without extension)
     * @return mixed
     * @throws FileNotFoundException If the file could not be found
     */
    public function loadRequire($name);

    /**
     * Reads a file into a string.
     *
     * @param  string $name The file name to load (without extension)
     * @return string
     * @throws FileNotFoundException If the file could not be found
     */
    public function load($name);
}
