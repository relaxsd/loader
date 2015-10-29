<?php namespace Relax\Loader;

use Illuminate\Contracts\Filesystem\Filesystem;

class FileFinder implements FileFinderInterface {

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * The array of active file paths.
	 *
	 * @var array
	 */
	protected $paths;

	/**
	 * The array of files that have been found.
	 *
	 * @var array
	 */
	protected $files = array();

	/**
	 * The namespace to file path hints.
	 *
	 * @var array
	 */
	protected $hints = array();

	/**
	 * Register a file extension with the finder.
	 *
	 * @var array
	 */
	protected $extensions = array('txt');

	/**
	 * Hint path delimiter value.
	 *
	 * @var string
	 */
	const HINT_PATH_DELIMITER = '::';

	/**
	 * Create a new file file loader instance.
	 *
	 * @param  \Illuminate\Filesystem\Filesystem  $filesystem
	 * @param  array  $paths
	 * @param  array  $extensions
	 */
	public function __construct(Filesystem $filesystem, array $paths, array $extensions = null)
	{
		$this->filesystem = $filesystem;
		$this->paths = $paths;

		if (isset($extensions))
		{
			$this->extensions = $extensions;
		}
	}

	/**
	 * Get the fully qualified location of the file.
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function find($name)
	{
		if (isset($this->files[$name])) return $this->files[$name];

		if (self::hasHintInformation($name = trim($name)))
		{
			return $this->files[$name] = $this->findNamedPathTemplate($name);
		}

		return $this->files[$name] = $this->findInPaths($name, $this->paths);
	}

	/**
	 * Get the path to a file with a named path.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function findNamedPathTemplate($name)
	{
		list($namespace, $file) = $this->getNamespaceSegments($name);

		return $this->findInPaths($file, $this->hints[$namespace]);
	}

	/**
	 * Get the segments of a file with a named path.
	 *
	 * @param  string  $name
	 * @return array
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function getNamespaceSegments($name)
	{
		$segments = explode(static::HINT_PATH_DELIMITER, $name);

		if (count($segments) != 2)
		{
			throw new \InvalidArgumentException("Template [$name] has an invalid name.");
		}

		if ( ! isset($this->hints[$segments[0]]))
		{
			throw new \InvalidArgumentException("No hint path defined for [{$segments[0]}].");
		}

		return $segments;
	}

	/**
	 * Find the given file in the list of paths.
	 *
	 * @param  string  $name
	 * @param  array   $paths
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function findInPaths($name, $paths)
	{
		foreach ((array) $paths as $path)
		{
			foreach ($this->getPossibleFileNames($name) as $file)
			{
				if ($this->filesystem->exists($filePath = $path.'/'.$file))
				{
					return $filePath;
				}
			}
		}

		throw new \InvalidArgumentException("Template [$name] not found.");
	}

	/**
	 * Get an array of possible file names.
	 *
	 * @param  string  $name
	 * @return array
	 */
	protected function getPossibleFileNames($name)
	{
		return array_map(function($extension) use ($name)
		{
			return str_replace('.', '/', $name).'.'.$extension;

		}, $this->extensions);
	}

	/**
	 * Add a location to the finder.
	 *
	 * @param  string  $location
	 * @return void
	 */
	public function addLocation($location)
	{
		$this->paths[] = $location;
	}

	/**
	 * Add a namespace hint to the finder.
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 * @return void
	 */
	public function addNamespace($namespace, $hints)
	{
		$hints = (array) $hints;

		if (isset($this->hints[$namespace]))
		{
			$hints = array_merge($this->hints[$namespace], $hints);
		}

		$this->hints[$namespace] = $hints;
	}

	/**
	 * Prepend a namespace hint to the finder.
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 * @return void
	 */
	public function prependNamespace($namespace, $hints)
	{
		$hints = (array) $hints;

		if (isset($this->hints[$namespace]))
		{
			$hints = array_merge($hints, $this->hints[$namespace]);
		}

		$this->hints[$namespace] = $hints;
	}

	/**
	 * Register an extension with the file finder.
	 *
	 * @param  string  $extension
	 * @return void
	 */
	public function addExtension($extension)
	{
		if (($index = array_search($extension, $this->extensions)) !== false)
		{
			unset($this->extensions[$index]);
		}

		array_unshift($this->extensions, $extension);
	}

	/**
	 * Returns whether or not the file specify a hint information.
	 *
	 * @param  string  $name
	 * @return boolean
	 */
	private static function hasHintInformation($name)
	{
		return strpos($name, static::HINT_PATH_DELIMITER) > 0;
	}

	/**
	 * Get the filesystem instance.
	 *
	 * @return \Illuminate\Filesystem\Filesystem
	 */
	public function getFilesystem()
	{
		return $this->filesystem;
	}

	/**
	 * Get the active file paths.
	 *
	 * @return array
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Get the namespace to file path hints.
	 *
	 * @return array
	 */
	public function getHints()
	{
		return $this->hints;
	}

	/**
	 * Get registered extensions.
	 *
	 * @return array
	 */
	public function getExtensions()
	{
		return $this->extensions;
	}

}
