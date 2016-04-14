<?php

namespace Modules\Authenticate\Http;

use Modules\Authenticate\Http\Middlewares\ExtendSessionByCookie;
use Modules\Authenticate\Http\Middlewares\SessionTimeout;

trait Kernel {

    public function pushMiddleware(){
        $router = app('router');

        // don't prepend middleware, because it effect to encrypt cookie
        $router->pushMiddlewareToGroup('web', ExtendSessionByCookie::class);

        $router->pushMiddlewareToGroup('web', SessionTimeout::class);
    }
}