<?php namespace Modules\Authenticate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Modules\Authenticate\Http\Requests\LoginFormRequest;
use Modules\Authenticate\Repositories\Auth;
use Modules\Authenticate\Repositories\Contracts\ActivationRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\UserRepositoryInterface;

class LoginController extends Controller {

    protected $messageBag;
    protected $auth;
    /**
     * LoginController constructor.
     */
    public function __construct(MessageBag $messageBag, Auth $auth)
    {
        $this->messageBag  = $messageBag;
        $this->auth        = $auth;
    }

    /**
     * post \auth\login
     * @param LoginFormRequest $request
     */
    public function login (LoginFormRequest $request){

        // The incoming request is valid...
        $data = $request->all();
        $remember = isset($data['remember']) ? true : false;

        if (! $user = $this->auth->findUserByCredentials($data['email'])) {
            //dd('user not found');
            $this->messageBag->add('error', 'user not found');
            return redirect()->route('auth.login')->withErrors($this->messageBag);
        }

        if (! $this->auth->isActivated($user)){
            //dd('user has not activated');
            $this->messageBag->add('error', 'user has not activated');
            return redirect()->route('auth.login')->withErrors($this->messageBag);
        }

        if (! $this->auth->checkPasswordHash($data['password'], $user->password)){
            //dd('wrong password');
            $this->messageBag->add('error', 'wrong password');
            return redirect()->route('auth.login')->withErrors($this->messageBag);
        }

        // everything ok, persist user
        $this->auth->login($user, $remember);

        // redirect to save cookie
        return redirect()->route('home');
    }

    /**
     * \auth\logout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout (){

        $this->auth->logout();
        // redirect
        return redirect()->route('home');
    }
}