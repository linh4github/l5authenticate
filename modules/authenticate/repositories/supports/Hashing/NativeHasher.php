<?php

namespace Modules\Authenticate\Repositories\Supports\Hashing;

use RuntimeException;

class NativeHasher implements HasherInterface
{
    /**
     * {@inheritDoc}
     */
    public function hash($value)
    {
        if (! $hash = password_hash($value, PASSWORD_DEFAULT)) {
            throw new RuntimeException('Error hashing value. Check system compatibility with password_hash().');
        }

        return $hash;
    }

    /**
     * {@inheritDoc}
     */
    public function check($value, $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }
}
