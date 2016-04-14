<?php

namespace Modules\Authenticate\Repositories;

use Closure;
use Modules\Authenticate\Models\User;
use Modules\Authenticate\Repositories\Contracts\UserRepositoryInterface;
use Modules\Authenticate\Repositories\Supports\Hashing\HasherInterface;

class IlluminateUserRepository implements UserRepositoryInterface
{
    use ReuseTrait;

    protected $hasher;

    /**
     * IlluminateUserRepository constructor.
     */
    public function __construct(
        HasherInterface $hasher
    )
    {
        $this->hasher = $hasher;
    }


    /**
     * @param array $data
     * @return bool|User
     */
    public function create(array $data)
    {
        $user = new User();

        $this->fill($user, $data);

        if ($user->save()) return $user;

        return false;
    }


    /**
     * @param $user
     * @param array $data
     * @return bool
     */
    public function update($user, array $data)
    {

        if (! $user instanceof User){
            $user = User::findOrFail($user);
        }

        $this->fill($user, $data);

        if ($user->save()) return $user;

        return false;
    }

    /**
     * Delete user
     * @param $user
     * @return mixed
     */
    public function delete($user)
    {
        if ($user instanceof User){
            $id = $user->id;
        }
        else { $id = $user; }

        $delete  = 'DELETE u.*, a.*, r.*, p.* FROM users u ';
        $delete .= 'INNER JOIN activations a ON a.user_id = u.id ';
        $delete .= 'INNER JOIN reminders r ON r.user_id = u.id ';
        $delete .= 'INNER JOIN persistences p ON p.user_id = u.id ';
        $delete .= 'WHERE u.id = ?';

        \DB::statement($delete, [$id]);
    }

    public function findByCredentials($login)
    {
        $user = User::where('email', $login)
                    ->orWhere('username', $login)->first();

        return $user ?: false;
    }

    /**
     * @param $password
     * @param $passHashed
     * @return bool
     */
    public function validatePasswordHash($password, $passHashed)
    {
        return $this->hasher->check($password, $passHashed);
    }

    /**
     * @param $id
     * @return new password
     */
    public function resetPassword($id)
    {
        $newPassword = $this->generateCode();

        $this->update($id, ['password' => $newPassword]);

        return $newPassword;
    }

    private function fill(User &$user, array $data){
        $allowKeys = $user->getFillable();
        // fill users table
        foreach ($data as $key => $value){
            if (in_array($key, $allowKeys)){
                if ($key === 'password') $user->{$key} = $this->hasher->hash($value);
                else $user->{$key} = $value;
            }
        }
    }
}
