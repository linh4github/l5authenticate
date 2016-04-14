<?php

namespace Modules\Authenticate\Repositories;

use DB;
use Modules\Authenticate\Models\User;
use Modules\Authenticate\Repositories\Contracts\ActivationRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\ReminderRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\UserRepositoryInterface;
use Modules\Authenticate\Repositories\Supports\Cookies\CookieInterface;
use Modules\Authenticate\Repositories\Supports\Sessions\SessionInterface;

class Auth {

    use ReuseTrait;

    protected $user;
    protected $activation;
    protected $persistence;
    protected $reminder;
    protected $session;
    protected $cookie;
    /**
     * Auth constructor.
     */
    public function __construct(
        UserRepositoryInterface $user, ActivationRepositoryInterface $activation,
        PersistenceRepositoryInterface $persistence, ReminderRepositoryInterface $reminder,
        SessionInterface $session, CookieInterface $cookie)
    {
        $this->user        = $user;
        $this->activation  = $activation;
        $this->persistence = $persistence;
        $this->reminder    = $reminder;
        $this->session     = $session;
        $this->cookie      = $cookie;
    }

    public function checkLogin(){
        $code = $this->session->get() ?: $this->cookie->get();

        if ($code) return $code;

        return false;
    }

    public function isActivated(User $user){
        return $this->activation->isActivated($user->id);
    }

    public function getLoginUser(){
        if ($code = $this->checkLogin()){
            return $this->persistence->findUserByPersistenceCode($code);
        }
        return false;
    }

    public function findUserByCredentials($login){
        return $this->user->findByCredentials($login);
    }

    public function checkPasswordHash($password, $hash){
        return $this->user->validatePasswordHash($password, $hash);
    }

    public function login(User $user, $remember = false)
    {
        $code = $this->generateCode();

        $this->session->put($code);

        if ($remember === true){
            $this->cookie->put($code);
        }

        $this->persistence->create($user, $code);
    }

    public function logout(){
        if ($code = $this->checkLogin()){
            $this->session->forget();
            $this->cookie->forget();

            $this->persistence->delete($code);
        }
    }

    public function register(array $data){

        DB::beginTransaction();
        try{
            $user = $this->user->create($data);

            $code = $this->generateCode();

            $activation = $this->activation->create(['user_id' => $user->id, 'code' => $code]);

            DB::commit();

            return [true, $activation];
        }
        catch(\Exception $e){
            DB::rollBack();

            return [false, $e->getMessage()];
        }

    }

    public function activate($user_id, $code){
        $status = $this->activation->checkLinkStatus($user_id, $code);

        if ($status[0] === true){
            $activation = $status[1];

            if (! $activation->completed === true) $this->activation->complete($user_id, $code);

            return [true, 'active completed'];
        }

        return [false, $status[1]];
    }
}