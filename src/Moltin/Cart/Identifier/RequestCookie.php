<?php namespace Jbn\Cart\Identifier;
/**
 * Class RequestCookie
 * @package jbn\Cart\Storage
 * @author Theo den Hollander <theo@hollandware.com>
 */
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class RequestCookie extends Cookie
{
    private $requestID;

    public function __construct()
    {
        $this->requestID = Config::get('jbncart.requestid', 'cookie');
    }

    /**
     * Get the current or new unique identifier
     * The cart http request overwrites the cookie identifier
     * @return string The identifier
     */
    public function get()
    {
        $identifierRequest = Input::get($this->requestID);

        if($identifierRequest)
        {
            setcookie('cart_identifier', $identifierRequest, 0, "/");
        }
        else
        {
            $identifierRequest = parent::get();
        }

        return $identifierRequest;
    }
}