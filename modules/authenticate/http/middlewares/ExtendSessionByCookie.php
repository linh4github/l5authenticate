<?php

namespace Modules\Authenticate\Http\Middlewares;

use Closure;
use Modules\Authenticate\Models\Repositories\Contracts\ActivationRepositoryInterface;
use Modules\Authenticate\Models\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Models\Repositories\Contracts\UserRepositoryInterface;
use Modules\Authenticate\Supports\Cookies\CookieInterface;
use Modules\Authenticate\Supports\Sessions\SessionInterface;

class ExtendSessionByCookie {

    protected $user;
    protected $persistence;
    protected $activation;
    protected $session;


    /**
     * Auth constructor.
     */
    public function __construct(
        UserRepositoryInterface $user,
        PersistenceRepositoryInterface $persistence,
        ActivationRepositoryInterface $activation,
        SessionInterface $session,
        CookieInterface $cookie)
    {
        $this->user        = $user;
        $this->persistence = $persistence;
        $this->activation  = $activation;
        $this->session     = $session;
        $this->cookie      = $cookie;
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
        // Perform action after because we need to know real cookie

        if (! $code = $this->cookie->get()) return $next($request);

        if (! $persistence = $this->persistence->findPersistenceByCode($code)) return $next($request);

        // add some checkpoint here

        // if everything is ok, extend the session
        if (! $this->session->get()) $this->session->put($code);

        //$user = $this->persistence->findUserByPersistenceCode($code);
        //$request->merge(compact('user'));

        return $next($request);
    }
}

