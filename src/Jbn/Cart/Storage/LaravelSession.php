<?php

/**
 * This file is part of Jbn Cart, a PHP package to handle
 * your shopping basket.
 *
 * Copyright (c) 2013 Jbn Ltd.
 * http://github.com/Jbn/cart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Jbn/cart
 * @author Chris Harvey <chris@molt.in>
 * @copyright 2013 Jbn Ltd.
 * @version dev
 * @link http://github.com/Jbn/cart
 *
 */

namespace Jbn\Cart\Storage;

use Jbn\Cart\Item;
use Session;

class LaravelSession implements \Jbn\Cart\StorageInterface
{
    protected $identifier;
    protected static $cart = array();

    public function restore()
    {
        $carts = Session::get('cart');

        if ($carts) static::$cart = $carts;
    }

    /**
     * Add or update an item in the cart
     * 
     * @param  Item   $item The item to insert or update
     * @return void
     */
    public function insertUpdate(Item $item)
    {
        static::$cart[$this->id][$item->identifier] = $item;

        $this->saveCart();
    }

    /**
     * Retrieve the cart data
     * 
     * @return array
     */
    public function &data($asArray = false)
    {
        $cart =& static::$cart[$this->id];

        if ( ! $asArray) return $cart;

        $data = $cart;

        foreach ($data as &$item) {
            $item = $item->toArray();
        }

        return $data;
    }

    /**
     * Check if the item exists in the cart
     * 
     * @param  mixed  $id
     * @return boolean
     */
    public function has($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return true;

        }

        return false;
    }

    /**
     * Get a single cart item by id
     * 
     * @param  mixed $id The item id
     * @return Item  The item class
     */
    public function item($identifier)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->identifier == $identifier) return $item;

        }

        return false;
    }

    /**
     * Returns the first occurance of an item with a given id
     * 
     * @param  string $id The item id
     * @return Item       Item object
     */
    public function find($id)
    {
        foreach (static::$cart[$this->id] as $item) {

            if ($item->id == $id) return $item;

        }

        return false;
    }
    
    /**
     * Remove an item from the cart
     * 
     * @param  mixed $id
     * @return void
     */
    public function remove($id)
    {
        unset(static::$cart[$this->id][$id]);

        $this->saveCart();
    }

    /**
     * Destroy the cart
     * 
     * @return void
     */
    public function destroy()
    {
        static::$cart[$this->id] = array();

        $this->saveCart();
    }

    /**
     * Set the cart identifier
     * 
     * @param string $identifier
     */
    public function setIdentifier($id)
    {
        $this->id = $id;

        if ( ! array_key_exists($this->id, static::$cart)) {
            static::$cart[$this->id] = array();
        }

        $this->saveCart();
    }

    /**
     * Return the current cart identifier
     * 
     * @return void
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    protected function saveCart()
    {
        $data = static::$cart;

        Session::put('cart', $data);
    }
}
