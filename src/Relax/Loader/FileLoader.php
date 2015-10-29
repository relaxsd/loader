<?php namespace Relax\Loader;

use Illuminate\Contracts\Filesystem\Filesystem;

class FileLoader implements FileLoaderInterface {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;
	/**
	 * The contents (!) of files that have been found.
	 *
	 * @var array
	 */
	protected $files = array();
	/**
	 * @var FileFinderInterface
	 */
	private $fileFinder;

	/**
	 * Create a new file file loader instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem $filesystem
	 * @param FileFinderInterface                $fileFinder
	 * @param  array                             $paths
	 * @param  array                             $extensions
	 */
	public function __construct(Filesystem $filesystem, FileFinderInterface $fileFinder, array $paths = null, array $extensions = null)
	{
		$this->filesystem = $filesystem;
		$this->fileFinder = $fileFinder;

		// Pass paths to the finder
		if ($paths) {
			foreach ($paths as $path) {
				$this->addLocation($path);
			}
		}

		// Pass extensions to the finder
		if ($extensions) {
			foreach ($extensions as $extension) {
				$this->addExtension($extension);
			}
		}
	}

	/**
	 * Add a location to the finder.
	 *
	 * @param  string $location
	 * @return void
	 */
	public function addLocation($location)
	{
		$this->fileFinder->addLocation($location);
	}

	/**
	 * Register an extension with the file finder.
	 *
	 * @param  string $extension
	 * @return void
	 */
	public function addExtension($extension)
	{
		$this->fileFinder->addExtension($extension);
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
		if ($cache && isset($this->files[$name])) {
			return $this->files[$name];
		}

		// Get file name
		if ( ! ($path = $this->fileFinder->find($name))) {
			return null;
		}

		// Cache and return file contents
		if ($cache) {
			return $this->files[$name] = $this->getRequire($path);
		}
		return $this->getRequire($path);
	}

	/**
	 * Get a file's contents by requiring it.
	 *
	 * @param  string $path
	 * @return mixed
	 */
	protected function getRequire($path)
	{
		return $this->filesystem->getRequire($path);
	}

	/**
	 * Add a namespace hint to the finder.
	 *
	 * @param  string       $namespace
	 * @param  string|array $hints
	 * @return void
	 */
	public function addNamespace($namespace, $hints)
	{
		$this->fileFinder->addNamespace($namespace, $hints);
	}

	/**
	 * Prepend a namespace hint to the finder.
	 *
	 * @param  string       $namespace
	 * @param  string|array $hints
	 * @return void
	 */
	public function prependNamespace($namespace, $hints)
	{
		$this->fileFinder->prependNamespace($namespace, $hints);
	}
}
