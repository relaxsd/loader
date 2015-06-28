<?php namespace Relax\Loader;

interface FileLoaderInterface {

	/**
	 * Load the file with the given name.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function load($name);

	/**
	 * Add a location to the loader.
	 *
	 * @param  string  $location
	 * @return void
	 */
	public function addLocation($location);

	/**
	 * Add a namespace hint to the loader.
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 * @return void
	 */
	public function addNamespace($namespace, $hints);

	/**
	 * Prepend a namespace hint to the loader.
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 * @return void
	 */
	public function prependNamespace($namespace, $hints);

	/**
	 * Add a valid template extension to the loader.
	 *
	 * @param  string  $extension
	 * @return void
	 */
	public function addExtension($extension);

}
