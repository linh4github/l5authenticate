<?php namespace Modules\Authenticate;

use Illuminate\Support\ServiceProvider;
use Modules\Administrator\Http\Kernel;
use Modules\Authenticate\Repositories\RepositoriesServiceProvider;

class AuthenticateServiceProvider extends ServiceProvider{

    use Kernel, RepositoriesServiceProvider;

    public function boot(){

        $configPath = realpath(__DIR__.'/config/authenticate.php');
        $this->mergeConfigFrom($configPath, 'authenticate');

        $viewPath = realpath(__DIR__.'/resources/views/');
        $this->loadViewsFrom($viewPath, 'authenticate');

        $langPath = realpath(__DIR__.'/resources/lang/');
        $this->loadTranslationsFrom($langPath, 'authenticate');

        // routes
        include __DIR__."/http/routes.php";
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositories();

        $this->pushMiddleware();
    }
}