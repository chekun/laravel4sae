<?php namespace Lavender\Cloud\Sina\Session;

use Illuminate\Session\SessionServiceProvider as DefaultSessionServiceProvider;

class SessionServiceProvider extends DefaultSessionServiceProvider {

	
	protected function registerSessionManager()
	{
		$this->app['session'] = $this->app->share(function($app)
		{
			return new SaeSessionManager($app);
		});
	}

}