<?php

/**
* This file is part of Jbn Cart for Laravel 4, a PHP
* package to provide a Service Provider and Facade for
* the Jbn\Cart package.
*
* Copyright (c) 2013 Jbn Ltd.
* http://github.com/Jbn/laravel-cart
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
* @package Jbn/laravel-cart
* @author Chris Harvey <chris@molt.in>
* @copyright 2013 Jbn Ltd.
* @version dev
* @link http://github.com/Jbn/laravel-cart
*
*/

namespace Jbn\Cart;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

use Jbn\Cart\Storage\LaravelSession as SessionStore;
use Jbn\Cart\Storage\Session as JbnSessionStore;
use Jbn\Cart\Storage\LaravelCache as CacheStore;
use Jbn\Cart\Storage\LaravelFile as FileStore;
use Jbn\Cart\Identifier\Cookie as CookieIdentifier;
use Jbn\Cart\Identifier\RequestCookie as CookieRequestIdentifier;

class CartServiceProvider extends ServiceProvider
{
    public function getStorageService()
    {
        $class = $this->app['config']->get('Jbncart.storage', 'session');
        switch ($class) {
            case 'session':
                return new SessionStore;
            break;
            
            case 'JbnSession':
                return new JbnSessionStore;
            break;

            case 'cache':
                return new CacheStore;
            break;

            case 'file':
                return new FileStore;
            break;

            default:
                return $this->app->make($class);
            break;
        }
    }

    public function getIdentifierService()
    {
        $class = $this->app['config']->get('Jbncart.identifier', 'cookie');
        switch ($class) {
            case 'requestcookie':
                return new CookieRequestIdentifier;
            break;

            case 'cookie':
                return new CookieIdentifier;
            break;

            default:
                return $this->app->make($class);
            break;
        }
    }

    public function register()
    {
        $that = $this;

        $this->app->singleton('cart', function() use ($that) {
            return new Cart($that->getStorageService(), $that->getIdentifierService());
        });

        $this->app->alias('cart', 'Jbn\Cart\Cart');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../../config/Jbncart.php' => config_path('Jbncart.php'),
        ]);
    }
}
