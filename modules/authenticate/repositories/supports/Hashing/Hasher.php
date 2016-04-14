<?php

namespace Modules\Authenticate\Repositories\Supports\Hashing;

trait Hasher
{
    /**
     * The salt length.
     *
     * @var int
     */
    protected $saltLength = 22;

    /**
     * Create a random string for a salt.
     *
     * @return string
     */
    protected function createSalt()
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $this->saltLength);
    }

    /**
     * Compares two strings $a and $b in length-constant time.
     *
     * @param  string  $a
     * @param  string  $b
     * @return boolean
     */
    protected function slowEquals($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);

        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $diff === 0;
    }
}
