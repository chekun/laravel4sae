<?php namespace Lavender\Cloud\Sina\View;

use Illuminate\View\ViewServiceProvider as DefaultViewServiceProvider;
use Lavender\Cloud\Sina\View\Compilers\SaeBladeCompiler;
use Lavender\Cloud\Sina\View\Engines\SaeCompilerEngine;

class ViewServiceProvider extends DefaultViewServiceProvider {
    
    public function registerBladeEngine($resolver)
    {
        $app = $this->app;
        
        $resolver->register('blade', function() use ($app) {
            $compiler = new SaeBladeCompiler($app['files'], $app['cache']);
            return new SaeCompilerEngine($compiler, $app['files']);
        });
    }
    
}