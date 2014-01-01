<?php namespace Lavender\Cloud\Sina\Cache;

use RuntimeException;
use Memcache;

class MemcacheConnector {

	/**
	 * Create a new Memcache connection.
	 *
	 * @return \Memcache
	 */
	public function connect()
	{
		$memcache = $this->getMemcache();

		// For sina app engine, we don't need to create server manually
		// SAE does it for use after we call mmecache_init()
		if ($memcache->getVersion() === false)
		{
			throw new RuntimeException("Could not establish Memcached connection.");
		}

		return $memcache;
	}

	/**
	 * Get a new Memcache instance.
	 *
	 * @return \Memcache
	 */
	protected function getMemcache()
	{
		return memcache_init();
	}

}