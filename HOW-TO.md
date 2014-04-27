# Let's run Laravel 4 on SAE

By [chekun](https://github.com/chekun) aka [@jeongee](http://weibo.com/234267695)

2014.1.1 晚首发与[SAE开发者论坛](http://cloudbbs.org/forum.php?mod=viewthread&tid=20003&extra=)

相信大家也都或多或少的了解过Laravel框架吧，本人以前是使用CodeIgniter框架的，2012年接触到了当时候的Laravel 3，立马就被它吸引了，关于它的介绍，我在这里不做详谈，感兴趣的可以到其[官网](http://laravel.com)查看了解.

对于SAE来说，云环境的一些限制让我们无法很直接自由的使用L4，先来看几点核心的：

- SAE限制了本地文件读写，L4对于一些缓存(如manifest)是采用了本地写的。
- 缓存类，L4并没有直接提供SAE可直接使用的缓存类。
- Session类，L4的Native Session并不是PHP的原生Session，用到了本地文件写，所以不可以行，这点可以结合缓存类进行解决。
- SAE默认session_start(),而使用了Symfony Session的L4，自然继承了seesion类和自动session_start冲突的问题。
- 模版引擎，依然是本地文件写的问题.

那么，想要在SAE上使用L4，那么必须解决以上几个问题。

幸运的是，PHP5的新特性和L4强大的扩展能力使得我们可以轻松解决上述问题。

这里我写了个[项目](https://github.com/chekun/laravel4sae)，可以让您的L4项目优雅的本地开发和运行与SAE平台。

本文不会详细介绍如何改造，感兴趣的可以直接去看源代码，本文说明一下，如何改造本地项目，做到本地开发和SAE线上运行无痛切换。

本地项目开发改造：

## 改造app/config/app.php

```
<?php

$app =  array(

	'debug' => false,

	'url' => 'http://www.dilicms.com',

	'timezone' => 'PRC',

	'locale' => 'zh-cn',

	'key' => 'x1RYfs4ArTE12sz7879mdvse471epPAA',

	'providers' => array(

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Foundation\Providers\CommandCreatorServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ComposerServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Foundation\Providers\KeyGeneratorServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Foundation\Providers\MaintenanceServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Foundation\Providers\OptimizeServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Foundation\Providers\PublisherServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Foundation\Providers\RouteListServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Foundation\Providers\ServerServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Foundation\Providers\TinkerServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',

		'Lavender\Cloud\Sina\Patcher\SaePatcherServiceProvider',
	),

	'manifest' => storage_path().'/meta',

	'aliases' => array(

		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',
		'Cache'           => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'     => 'Illuminate\Support\ClassLoader',
		'Config'          => 'Illuminate\Support\Facades\Config',
		'Controller'      => 'Illuminate\Routing\Controllers\Controller',
		'Cookie'          => 'Illuminate\Support\Facades\Cookie',
		'Crypt'           => 'Illuminate\Support\Facades\Crypt',
		'DB'              => 'Illuminate\Support\Facades\DB',
		'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
		'Event'           => 'Illuminate\Support\Facades\Event',
		'File'            => 'Illuminate\Support\Facades\File',
		'Form'            => 'Illuminate\Support\Facades\Form',
		'Hash'            => 'Illuminate\Support\Facades\Hash',
		'HTML'            => 'Illuminate\Support\Facades\HTML',
		'Input'           => 'Illuminate\Support\Facades\Input',
		'Lang'            => 'Illuminate\Support\Facades\Lang',
		'Log'             => 'Illuminate\Support\Facades\Log',
		'Mail'            => 'Illuminate\Support\Facades\Mail',
		'Paginator'       => 'Illuminate\Support\Facades\Paginator',
		'Password'        => 'Illuminate\Support\Facades\Password',
		'Queue'           => 'Illuminate\Support\Facades\Queue',
		'Redirect'        => 'Illuminate\Support\Facades\Redirect',
		'Redis'           => 'Illuminate\Support\Facades\Redis',
		'Request'         => 'Illuminate\Support\Facades\Request',
		'Response'        => 'Illuminate\Support\Facades\Response',
		'Route'           => 'Illuminate\Support\Facades\Route',
		'Schema'          => 'Illuminate\Support\Facades\Schema',
		'Seeder'          => 'Illuminate\Database\Seeder',
		'Session'         => 'Illuminate\Support\Facades\Session',
		'Str'             => 'Illuminate\Support\Str',
		'URL'             => 'Illuminate\Support\Facades\URL',
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',
	),

);

$app['sae'] = false;

if (defined('SAE_ACCESSKEY') && (substr(SAE_ACCESSKEY, 0, 4 ) != 'kapp')) {

	$removeProviders = array(
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Log\LogServiceProvider'
	);

	foreach ($app['providers'] as $key => $provider) {

		if (in_array($provider, $removeProviders)) {

			unset($app['providers'][$key]);

		}

	}

	$app['providers'] = array_merge($app['providers'], array(
	    'Lavender\Cloud\Sina\Log\LogServiceProvider',
        'Lavender\Cloud\Sina\Cache\CacheServiceProvider',
        'Lavender\Cloud\Sina\Storage\StorageServiceProvider',
        'Lavender\Cloud\Sina\View\ViewServiceProvider',
        'Lavender\Cloud\Sina\Session\SessionServiceProvider',
	));

	$app['aliases']['Storage'] = 'Lavender\Cloud\Sina\Storage\Storage';
    
    $app['sae'] = true;

}

return $app;
```

只要在判断是SAE环境下的时候注入相应的类取代L4自带的类。

## 配置app/config/database.php

```
'mysql' => array(
	'driver'    => 'mysql',
	'host'      => SAE_MYSQL_HOST_M,
	'port'      => SAE_MYSQL_PORT,
	'database'  => SAE_MYSQL_DB,
	'username'  => SAE_MYSQL_USER,
	'password'  => SAE_MYSQL_PASS,
	'charset'   => 'utf8',
	'collation' => 'utf8_general_ci',
	'prefix'    => '',
),
```

使用SAE数据库设置常量配置我们的数据库设置。

## 配置app/config/cache.php和app/config/session.php

```
'driver' => 'memcache',
```

使用提供的memcache驱动。

## 配置本地开发环境

L4支持自定义开发环境，一般本地开发就是local配置啦，这里可以在bootstrap/start.php中设置。

然后我们在app/config/local文件夹下配置本地开发环境配置。

经过以上配置，我们搭建了本地开发和SAE运行环境的配置分离。

然后就是尽情开发啦~~~~~~~~~

# 上传代码到SAE

这里需要整个项目打包上传，推荐使用SAE的SVN上传。

# 等下，上传之前还有重要的一步

L4使用的包中，有涉及到直接修改php.ini的操作，这个东西会导致程序直接抛错。

不用担心，上传之前只需执行:

```
php artisan sae:patch
```

一切都变得美好了。

小技巧：你也可以通过配置composer.json，以便每次更新后会自动执行它：

```
"scripts":{
    "post-update-cmd":[
        "php artisan clear-compiled",
        "php artisan sae:patch",
        "php artisan optimize",
    ]
},
```

# 最后

做个小宣传，也是我的运行SAE上的L4的程序，当个DEMO吧～～

[www.dilicms.com](http://www.dilicms.com)

