<?php namespace VaahCms\Modules\Basic\Providers;


use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use VaahCms\Modules\Basic\Providers\RouteServiceProvider;
use VaahCms\Modules\Basic\Providers\EventServiceProvider;

class BasicServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        $this->registerMiddleware($router);
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerAssets();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerSeeders();
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $this->registerHelpers();
        $this->registerLibraries();

    }

    /**
     *
     */
    private function registerMiddleware($router) {

        //register middleware
        //$router->aliasMiddleware('sample.middleware', \Basic\Http\Middleware\SampleMiddleware::class);

    }

    /**
     *
     */
    private function registerHelpers() {

        //load all the helpers
        foreach (glob(__DIR__.'/../Helpers/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     *
     */
    private function registerLibraries()
    {
        //load all the helpers
        foreach (glob(__DIR__.'/Libraries/*.php') as $filename){
            require_once($filename);
        }
    }


    /**
     *
     */
    private function registerSeeders() {

        //load all the seeds
        foreach (glob(__DIR__.'/../Database/Seeds/*.php') as $filename){
            require_once($filename);
        }

    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('basic.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'basic'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('/views/vaahcms/modules/basic');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/views/vaahcms/modules/basic';
        }, \Config::get('view.paths')), [$sourcePath]), 'basic');

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerAssets()
    {

        $sourcePath = __DIR__.'/../Resources/assets';

        $desPath = public_path('vaahcms/modules/basic/assets');

        $this->publishes([
            $sourcePath => $desPath
        ],'assets');


    }


    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('/lang/vaahcms/modules/basic');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'basic');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'basic');
        }
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeDirectives()
    {

        /*
        \Blade::directive('hello', function ($expression) {
            return "<?php echo 'Hello ' . {$expression}; ?>";
        });
        */

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function registerBladeComponents()
    {

        /*
        \Blade::component('example', Example::class);
        */

    }

}
