<?php namespace Relax\Loader;

use Illuminate\Contracts\Events\Dispatcher;
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
	 * @var Dispatcher
	 */
	private $events;

	/**
	 * Create a new file file loader instance.
	 *
	 * @param  Filesystem         $filesystem
	 * @param FileFinderInterface $fileFinder
	 * @param Dispatcher          $events
	 */
	public function __construct(Filesystem $filesystem, FileFinderInterface $fileFinder, Dispatcher $events)
	{
		$this->filesystem = $filesystem;
		$this->fileFinder = $fileFinder;
		$this->events     = $events;
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
		return $this->load($name, $cache, true);
	}

	/**
	 * Load the contents of a file.
	 *
	 * @param  string $name
	 * @param bool    $cache
	 * @param bool    $require
	 * @return string
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	public function load($name, $cache = true, $require = false)
	{
		// Load from cache
		if ($cache && isset($this->cache[$name])) {
			return $this->cache[$name];
		}

		// Get file name
		if ( ! ($path = $this->fileFinder->find($name))) {
			return null;
		}

		// Get contents
		$contents = $require
			? $this->filesystem->get($path)
			: $this->filesystem->getRequire($path);

		// Cache and return file contents
		if ($cache) {
			$this->cache[$name] = $contents;
		}

		$this->events->fire('relax.loader.loaded', [$name, $contents, $this, $cache]);

		return $contents;
	}

}
