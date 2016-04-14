<?php

namespace Modules\Authenticate\Repositories\Supports\Sessions;

use Modules\Authenticate\Supports\Sessions\SessionInterface;

class NativeSession implements SessionInterface
{
    /**
     * The session key.
     *
     * @var string
     */
    protected $key = 'module_authenticate';

    /**
     * Creates a new native session driver for Sentinel.
     *
     * @param  string  $key
     * @return void
     */
    public function __construct($key = null)
    {
        if (isset($key)) {
            $this->key = $key;
        }

        $this->startSession();
    }

    /**
     * Called upon destruction of the native session handler.
     *
     * @return void
     */
    public function __destruct()
    {
        $this->writeSession();
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        $this->setSession($value);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->getSession();
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->forgetSession();
    }

    /**
     * Starts the session if it does not exist.
     *
     * @return void
     */
    protected function startSession()
    {
        // Check that the session hasn't already been started
        if (session_id() == '' && ! headers_sent()) {
            session_start();
        }
    }

    /**
     * Writes the session.
     *
     * @return void
     */
    protected function writeSession()
    {
        session_write_close();
    }

    /**
     * Unserializes a value from the session and returns it.
     *
     * @return mixed.
     */
    protected function getSession()
    {
        if (isset($_SESSION[$this->key])) {
            $value = $_SESSION[$this->key];

            if ($value) {
                return unserialize($value);
            }
        }
    }

    /**
     * Interacts with the $_SESSION global to set a property on it.
     * The property is serialized initially.
     *
     * @param  mixed  $value
     * @return void
     */
    protected function setSession($value)
    {
        $_SESSION[$this->key] = serialize($value);
    }

    /**
     * Forgets the Sentinel session from the global $_SESSION.
     *
     * @return void
     */
    protected function forgetSession()
    {
        if (isset($_SESSION[$this->key])) {
            unset($_SESSION[$this->key]);
        }
    }
}
