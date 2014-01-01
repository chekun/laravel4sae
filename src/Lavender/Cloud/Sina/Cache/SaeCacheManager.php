<?php namespace Lavender\Cloud\Sina\Cache;

use Illuminate\Cache\CacheManager;

class SaeCacheManager extends CacheManager {

	/**
	 * Create an instance of the Memcached cache driver.
	 *
	 * @return \Lavender\Cloud\Sina\Cache\MemcacheStore
	 */
	protected function createMemcacheDriver()
	{
		$memcache = $this->app['memcache.connector']->connect();

		return $this->repository(new MemcacheStore($memcache, $this->getPrefix()));
	}

	/**
	 * Create an instance of the Kvdb cache driver.
	 *
	 * @return \Lavender\Cloud\Sina\Cache\KvdbStore
	 */
	protected function createKvdbDriver()
	{
		$kvdb = $this->app['kvdb.connector']->connect();

		return $this->repository(new KvdbStore($kvdb, $this->getPrefix()));
	}

}