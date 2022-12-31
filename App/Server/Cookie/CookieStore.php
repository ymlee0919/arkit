<?php

require 'Cookie.php';

class CookieStore
{
    /**
     * The cookie collection.
     *
     * @var array<string, Cookie>
     */
    protected array $cookies = [];

    /**
     * Creates a CookieStore from an array of `Set-Cookie` headers.
     *
     * @param string[] $headers
     *
     * @return self
     */
    public static function fromCookieHeaders(array $headers) : self
    {
        /**
         * @var Cookie[] $cookies
         */
        $cookies = [];

        foreach ($headers as $header)
        {
            $cookie = Cookie::fromHeaderString($header);
            if(!is_null($cookie))
                $cookies[] = $cookie;
            else
                App::$Logs->notice('Invalid cookie name from header: ' . $header);
        }

        return new CookieStore($cookies);
    }

    public static function fromServerRequest(string ...$skippingList) : self
    {
        $cookies = [];

        foreach ($_COOKIE as $name => $value)
        {
            if(in_array($name, $skippingList))
                continue;

            $cookie = Cookie::create($name, $value);
            if(is_null($cookie))
                App::$Logs->warning('Invalid cookie name set', ['Cookie Name', $name]);
            else
                $cookies[] = $cookie;
        }

        return new CookieStore($cookies);
    }

    /**
     * @param Cookie[] $cookies
     */
    final public function __construct(array $cookies)
    {
        // Validate each cookie
        foreach ($cookies as $index => $cookie)
        {
            $type = is_object($cookie) ? get_class($cookie) : gettype($cookie);

            if (! $cookie instanceof Cookie)
                App::$Logs->alert("Invalid Cookie set at [$index]. Expected Cookie object, $type set");
            else
                $this->cookies[$cookie->getId()] = $cookie;
        }
    }

    /**
     * Checks if a `Cookie` object identified by name is present in the collection.
     *
     * @param string $name The name of the cookie to search
     * @param ?string $value (Optional) The value of the cookie
     * @return bool
     */
    public function has(string $name, ?string $value = null): bool
    {
        foreach ($this->cookies as $cookie) {
            if ($cookie->getName() !== $name)
                continue;

            if ($value === null) {
                return true; // for BC
            }

            return $cookie->getValue() === $value;
        }

        return false;
    }

    /**
     * Retrieves an instance of `Cookie` identified by a name.
     * Return null if not find it
     *
     * @param string $name The name of the cookie to search
     * @return ?Cookie
     */
    public function get(string $name): ?Cookie
    {
        foreach ($this->cookies as $cookie) {
            if ($cookie->getName() === $name) {
                return $cookie;
            }
        }

        return null;
    }

    /**
     * Store a new cookie and return a new collection. The original collection
     * is left unchanged.
     *
     * @param Cookie $cookie
     * @return static
     */
    public function put(Cookie $cookie) : static
    {
        $store = clone $this;

        $store->cookies[$cookie->getId()] = $cookie;

        return $store;
    }

    /**
     * Removes a cookie from a collection and returns an updated collection.
     * The original collection is left unchanged.
     *
     * Removing a cookie from the store **DOES NOT** delete it from the browser.
     * If you intend to delete a cookie *from the browser*, you must put an empty
     * value cookie with the same name to the store.
     *
     * @param string $name
     * @return static
     */
    public function remove(string $name) : static
    {
        $default = Cookie::setDefaults();

        $id = implode(';', [$name, $default['path'], $default['domain']]);

        $store = clone $this;

        foreach (array_keys($store->cookies) as $index) {
            if ($index === $id) {
                unset($store->cookies[$index]);
            }
        }

        return $store;
    }

    /**
     * Set the value to empty and the expiration in the pass for deleting
     *
     * @param string $name
     * @return static
     */
    public function removeFromBrowser(string $name) : static
    {
        $default = Cookie::setDefaults();

        $id = implode(';', [$name, $default['path'], $default['domain']]);

        $store = clone $this;

        foreach (array_keys($store->cookies) as $index) {
            if ($index === $id) {
                $store->cookies[$index]->setValue('')->setExpired();
            }
        }

        return $store;
    }
}