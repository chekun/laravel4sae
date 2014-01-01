<?php namespace Lavender\Cloud\Sina\Cache;

use RuntimeException;
use SaeKV;

class MemcacheConnector {

	/**
	 * Create a new kvdb connection.
	 *
	 * @return \SaeKV
	 */
	public function connect()
	{
		$kvdb = $this->getKvdb();

		return $kvdb;
	}

	/**
	 * Get a new kvdb instance.
	 *
	 * @return \SaeKV
	 */
	protected function getKvdb()
	{
	    $kvdb = new SaeKV();
	    $kvdb->init();
	    if (! $kvdb) {
	        
	        throw new RuntimeException("Could not establish kvdb connection.");
	        
	    }
	    return $kvdb;
	}

}