<?php

namespace Modules\Authenticate\Http\Middlewares;

use Closure;
use Illuminate\Session\Store;
use Modules\Authenticate\Models\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Models\Repositories\Contracts\UserRepositoryInterface;

class SessionTimeout {

    protected $user;
    protected $persistence;
    protected $activation;
    protected $session;

    protected $timeout = 3600; // seconds = 60 minutes


    /**
     * Auth constructor.
     */
    public function __construct(
        UserRepositoryInterface $user,
        PersistenceRepositoryInterface $persistence,
        Store $session)
    {
        $this->user        = $user;
        $this->persistence = $persistence;
        $this->session     = $session;
    }


    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if user is log in
        if ($this->session->has(config('authenticate.session'))){

            if (! $this->session->get('lastActivity')){
                $this->session->put('lastActivity', time());
            }

            if (time() - $this->session->get('lastActivity') > $this->timeout){
                // logout the user
                // need be return respond to apply cookie
                $this->persistence->forget();

                $this->session->forget('lastActivity');
            }
            else{
                $this->session->put('lastActivity', time());
            }
        }


        return $next($request);
    }
}

