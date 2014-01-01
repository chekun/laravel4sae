<?php namespace Lavender\Cloud\Sina\Patcher;

use Illuminate\Support\ServiceProvider;

class SaePatcherServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['command.sae.patch'] = $this->app->share(function($app)
		{
			return new Commands\Patch();
		});
		$this->commands('command.sae.patch');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('command.sae.patch');
	}

}