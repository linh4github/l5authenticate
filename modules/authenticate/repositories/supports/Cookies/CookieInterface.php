<?php

namespace Modules\Authenticate\Repositories\Supports\Cookies;

interface CookieInterface
{
    /**
     * Put a value in the Authenticate cookie (to be stored until it's cleared).
     *
     * @param  mixed  $value
     * @return void
     */
    public function put($value);

    /**
     * Returns the Authenticate cookie value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Remove the Authenticate cookie.
     *
     * @return void
     */
    public function forget();
}
