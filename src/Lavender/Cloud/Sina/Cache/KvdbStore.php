<?php namespace Lavender\Cloud\Sina\Cache;

use Illuminate\Cache\StoreInterface;
use Illuminate\Cache\Section;
use SaeKV;

class KvdbStore implements StoreInterface {

	/**
	 * The kvdb instance.
	 *
	 * @var \SaeKV
	 */
	protected $kvdb;

	/**
	 * A string that should be prepended to keys.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Create a new Kvdb store.
	 *
	 * @param  \SaeKV  $Kvdb
	 * @param  string     $prefix
	 * @return void
	 */
	public function __construct(SaeKV $kvdb, $prefix = '')
	{
		$this->kvdb = $kvdb;
		$this->prefix = strlen($prefix) > 0 ? $prefix.':' : '';
	}

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function get($key)
	{
		$value = $this->kvdb->get($this->prefix.$key);
		return $value;
	}

	/**
	 * Store an item in the cache for a given number of minutes.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function put($key, $value, $minutes = 0)
	{
		$this->kvdb->set($this->prefix.$key, $value);
	}
	
	
	/**
	 * Increment or Decrement the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  string  $direction
	 * @return integer
	 */
	private function incrementOrDecrement($key, $value, $direction = '+')
	{
	    $target = $this->kvdb->get($this->prefix.$key);
	    $direction == '-' ? ($target -= $value) : ($target += $value);
	    $this->kvdb->set($this->prefix.$key, $target);
		return $target;
	}

	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return integer
	 */
	public function increment($key, $value = 1)
	{
	    return $this->incrementOrDecrement($key, $value, '+');
	}

	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return integer
	 */
	public function decrement($key, $value = 1)
	{
		return $this->incrementOrDecrement($key, $value, '-');
	}

	/**
	 * Store an item in the cache indefinitely.
	 * This is just an alias for put.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function forever($key, $value)
	{
		return $this->put($key, $value);
	}

	/**
	 * Remove an item from the cache.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function forget($key)
	{
		$this->kvdb->delete($this->prefix.$key);
	}

	/**
	 * Remove all items from the cache.
	 *
	 * @return void
	 */
	public function flush()
	{
		//not supported.
	}

	/**
	 * Begin executing a new section operation.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Cache\Section
	 */
	public function section($name)
	{
		return new Section($this, $name);
	}

	/**
	 * Get the underlying Kvdb connection.
	 *
	 * @return \SaeKV
	 */
	public function getKvdb()
	{
		return $this->kvdb;
	}

	/**
	 * Get the cache key prefix.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

}