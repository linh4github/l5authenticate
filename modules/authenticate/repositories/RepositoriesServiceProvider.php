<?php

namespace Modules\Authenticate\Repositories;

use Modules\Authenticate\Repositories\Contracts\ActivationRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\PersistenceRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\ReminderRepositoryInterface;
use Modules\Authenticate\Repositories\Contracts\UserRepositoryInterface;
use Modules\Authenticate\Repositories\Supports\Cookies\CookieInterface;
use Modules\Authenticate\Repositories\Supports\Cookies\IlluminateCookie;
use Modules\Authenticate\Repositories\Supports\Hashing\HasherInterface;
use Modules\Authenticate\Repositories\Supports\Hashing\NativeHasher;
use Modules\Authenticate\Repositories\Supports\Sessions\IlluminateSession;
use Modules\Authenticate\Repositories\Supports\Sessions\SessionInterface;

trait RepositoriesServiceProvider {

    function registerRepositories(){
        // Hashers
        $this->app->singleton(HasherInterface::class, function ($app) {
            return new NativeHasher;
        });

        // Sessions
        $this->app->singleton(SessionInterface::class, function ($app) {
            return new IlluminateSession($app['session.store'], config('authenticate.session'));
        });

        // Cookies
        $this->app->singleton(CookieInterface::class, function ($app) {
            return new IlluminateCookie($app['request'], $app['cookie'], config('authenticate.cookie'));
        });

        // User Repository
        $this->app->singleton(UserRepositoryInterface::class, function ($app) {
            return new IlluminateUserRepository(
                $app[HasherInterface::class]
            );
        });

        // Activation Repository
        $this->app->singleton(ActivationRepositoryInterface::class, function ($app) {
            return new IlluminateActivationRepository(config('authenticate.activations.expires'));
        });

        // Persistence Repository
        $this->app->singleton(PersistenceRepositoryInterface::class, function ($app) {
            return new IlluminatePersistenceRepository(
                $app[SessionInterface::class], $app[CookieInterface::class]);
        });

        // Reminder Repository
        $this->app->singleton(ReminderRepositoryInterface::class, function ($app) {
            return new IlluminateReminderRepository(config('authenticate.reminders.expires'));
        });
    }
}