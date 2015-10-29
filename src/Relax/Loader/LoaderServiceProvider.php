<?php namespace Way\Generators;

use Illuminate\Support\ServiceProvider;

class LoaderServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Booting
	 */
	public function boot()
	{
		$this->package('relax/loader');
	}

	/**
	 * Register the commands
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Relax\Loader\FileFinderInterface', 'Relax\Loader\FileFinder');
		$this->app->bind('Relax\Loader\FileLoaderInterface', 'Relax\Loader\FileLoader');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}
}
