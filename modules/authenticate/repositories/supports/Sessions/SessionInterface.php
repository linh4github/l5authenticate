<?php

namespace Modules\Authenticate\Repositories\Supports\Sessions;

interface SessionInterface
{
    /**
     * Put a value in the Authenticate session.
     *
     * @param  mixed  $value
     * @return void
     */
    public function put($value);

    /**
     * Returns the Authenticate session value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Removes the Authenticate session.
     *
     * @return void
     */
    public function forget();
}
