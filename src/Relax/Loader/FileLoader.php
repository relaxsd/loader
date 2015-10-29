<?php namespace Relax\Loader;

use Illuminate\Filesystem\Filesystem;

class FileLoader implements FileLoaderInterface {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;
	/**
	 * Cache containing the contents (!) of files that were loaded.
	 *
	 * @var array
	 */
	protected $cache = array();
	/**
	 * @var FileFinderInterface
	 */
	protected $fileFinder;

	/**
	 * Create a new file file loader instance.
	 *
	 * @param  Filesystem $filesystem
	 * @param FileFinderInterface                $fileFinder
	 */
	public function __construct(Filesystem $filesystem, FileFinderInterface $fileFinder)
	{
		$this->filesystem = $filesystem;
		$this->fileFinder = $fileFinder;
	}

	/**
	 * Load a file's contents by requiring it.
	 *
	 * @param  string $name
	 * @param bool    $cache
	 * @return string
	 */
	public function loadRequire($name, $cache = true)
	{
		// Load from cache
		if ($cache && isset($this->cache[$name])) {
			return $this->cache[$name];
		}

		// Get file name
		if ( ! ($path = $this->fileFinder->find($name))) {
			return null;
		}

		// Cache and return file contents
		if ($cache) {
			return $this->cache[$name] = $this->filesystem->getRequire($path);
		}
		return $this->filesystem->getRequire($path);
	}

	/**
	 * Load the contents of a file.
	 *
	 * @param  string $name
	 * @param bool    $cache
	 * @return string
	 */
	public function load($name, $cache = true)
	{
		// Load from cache
		if ($cache && isset($this->cache[$name])) {
			return $this->cache[$name];
		}

		// Get file name
		if ( ! ($path = $this->fileFinder->find($name))) {
			return null;
		}

		// Cache and return file contents
		if ($cache) {
			return $this->cache[$name] = $this->filesystem->get($path);
		}
		return $this->filesystem->get($path);
	}
}
