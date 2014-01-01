<?php namespace Lavender\Cloud\Sina\Session;

use Illuminate\Session\SessionManager;

class SaeSessionManager extends SessionManager {


	/**
	 * Create an instance of the Memcache session driver.
	 *
	 * @return \Illuminate\Session\Store
	 */
	protected function createMemcacheDriver()
	{
        //get rid of session.autostart directive is on matters. 
        $config = \Config::get('session');
        session_set_cookie_params($config['lifetime'], $config['path'], substr($config['domain'], 1));
		
        return $this->createCacheBased('memcache');
	}

}