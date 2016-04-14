<?php

namespace Modules\Authenticate\Repositories;

use Modules\Authenticate\Models\Persistence;
use Modules\Authenticate\Models\User;
use Modules\Authenticate\Repositories\Contracts\PersistenceRepositoryInterface;

class IlluminatePersistenceRepository implements PersistenceRepositoryInterface {

    /**
     * IlluminatePersistenceRepository constructor.
     */
    public function __construct()
    {

    }

    public function delete($code){
        return Persistence::where('code', $code)->delete();
    }

    public function findPersistenceByCode($code){
        return Persistence::where('code', $code)->first() ?: false;
    }

    /**
     * Finds a user by persistence code.
     *
     * @param  string $code
     */
    public function findUserByPersistenceCode($code)
    {
        return $this->findPersistenceByCode($code)->user ?: false;
    }

    public function create(User $user, $code)
    {
        // save record
        $persistence = new Persistence();
        $persistence->user_id = $user->id;
        $persistence->code = $code;

        return $persistence->save();
    }
}