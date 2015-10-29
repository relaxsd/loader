<?php namespace Relax\Loader;

interface FileLoaderInterface {

	/**
	 * Load the file with the given name.
	 *
	 * @param  string $name
	 * @param bool    $cache
	 * @return string
	 */
	public function load($name, $cache);

	/**
	 * Load a file's contents by requiring it.
	 *
	 * @param  string $name
	 * @param bool    $cache
	 * @return string
	 */
	public function loadRequire($name, $cache = true);

}
