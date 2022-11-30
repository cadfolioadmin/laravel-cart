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

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }
}