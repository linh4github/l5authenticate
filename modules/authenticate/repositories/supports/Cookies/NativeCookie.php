<?php

namespace Modules\Authenticate\Repositories\Supports\Cookies;

class NativeCookie implements CookieInterface
{
    /**
     * The cookie options.
     *
     * @var array
     */
    protected $options = [
        'name'      => 'module_authenticate',
        'domain'    => '',
        'path'      => '/',
        'secure'    => false,
        'http_only' => false,
    ];

    /**
     * Create a new cookie driver.
     *
     * @param  string|array  $options
     * @return void
     */
    public function __construct($options = [])
    {
        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
        } else {
            $this->options['name'] = $options;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        $this->setCookie($value, $this->minutesToLifetime(2628000));
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->getCookie();
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->put(null, -2628000);
    }

    /**
     * Takes a minutes parameter (relative to now)
     * and converts it to a lifetime (unix timestamp).
     *
     * @param  int  $minutes
     * @return int
     */
    protected function minutesToLifetime($minutes)
    {
        return time() + ($minutes * 60);
    }

    /**
     * Returns a PHP cookie.
     *
     * @return mixed
     */
    protected function getCookie()
    {
        if (isset($_COOKIE[$this->options['name']])) {
            $value = $_COOKIE[$this->options['name']];

            if ($value) {
                return unserialize($value);
            }
        }
    }

    /**
     * Sets a PHP cookie.
     *
     * @param  mixed  $value
     * @param  int  $lifetime
     * @param  string  $path
     * @param  string  $domain
     * @param  bool  $secure
     * @param  bool  $httpOnly
     * @return void
     */
    protected function setCookie($value, $lifetime, $path = null, $domain = null, $secure = null, $httpOnly = null)
    {
        setcookie(
            $this->options['name'],
            serialize($value),
            $lifetime,
            $path ?: $this->options['path'],
            $domain ?: $this->options['domain'],
            $secure ?: $this->options['secure'],
            $httpOnly ?: $this->options['http_only']
        );
    }
}
