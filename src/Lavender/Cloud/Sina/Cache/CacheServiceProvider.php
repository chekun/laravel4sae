<?php namespace Lavender\Cloud\Sina\Cache;

use Illuminate\Cache\CacheServiceProvider as DefaultCacheServiceProvider;

class CacheServiceProvider extends DefaultCacheServiceProvider {

    public function register()
    {
        $this->app['cache'] = $this->app->share(function($app)
        {
            return new SaeCacheManager($app);
        });

        $this->app['memcached.connector'] = $this->app->share(function()
        {
            return new MemcachedConnector;
        });

        
        $this->app['memcache.connector'] = $this->app->share(function() {
            return new MemcacheConnector;
        });

        $this->app['kvdb.connector'] = $this->app->share(function() {
            return new KvdbConnector;
        });

        $this->registerCommands();
    }
    
    public function provides()
    {
        $provides = parent::provides();
        array_push($provides, 'memcache.connector', 'kvdb.connector');
        return $provides;
    }
    
}