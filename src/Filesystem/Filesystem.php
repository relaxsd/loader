<?php

namespace Relax\Loader\Filesystem;

class Filesystem implements \Relax\Loader\Contracts\Filesystem
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
    public function make($file, $content, $overwrite = true)
    {
        if ((!$overwrite) && $this->exists($file)) {
            throw new FileAlreadyExistsException;
        }

        $path = dirname($file);
        if (!$this->exists($path)) {
            $this->makeDirectory($path, 0777, true);
        }

        return file_put_contents($file, $content);
    }

    /**
     * Determines if a file exists.
     *
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * Reads a file into a string.
     *
     * @param $file
     * @throws FileNotFoundException
     * @return string
     */
    public function get($file)
    {
        if (!$this->exists($file)) {
            throw new FileNotFoundException($file);
        }

        return file_get_contents($file);
    }

    /**
     * Create a directory.
     *
     * @param  string $path
     * @param  int $mode
     * @param  bool $recursive
     * @param  bool $force
     * @return bool
     */
    public function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }
}
