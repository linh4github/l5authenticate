<?php

namespace Modules\Authenticate\Http\Middlewares;

use Closure;
use Illuminate\Session\Store;
use Modules\Authenticate\Models\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Models\Repositories\Contracts\UserRepositoryInterface;

class CheckUserLogin {

    protected $session;


    /**
     * Auth constructor.
     */
    public function __construct(Store $session)
    {
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
        if ($this->session->has(config('authenticate.session'))){

            // redirect if route login
            return redirect()->route('home');
        }
        return $next($request);
    }
}

