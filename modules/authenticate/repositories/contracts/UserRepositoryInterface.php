<?php

namespace Modules\Authenticate\Repositories\Contracts;

interface UserRepositoryInterface
{
    /**
     *
     * @return User
     */
    public function findByCredentials($login);


    public function validatePasswordHash($password, $passHashed);

    /**
     * @param $id
     * @return new password
     */
    public function resetPassword($id);

    /**
     * Creates a user.
     */
    public function create(array $data);

    /**
     * Updates a user.
     */
    public function update($id, array $data);

    /**
     * Delete user
     */
    public function delete($id);
}
