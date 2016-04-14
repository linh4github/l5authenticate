<?php

namespace Modules\Authenticate\Repositories\Contracts;

use Modules\Authenticate\Models\User;

interface PersistenceRepositoryInterface
{
    /**
     * @param $code
     * @return  | false
     */
    public function findPersistenceByCode($code);
    /**
     * Finds a user by persistence code.
     *
     * @param  string  $code
     */
    public function findUserByPersistenceCode($code);

    public function delete($code);

    public function create(User $user, $code);
}
