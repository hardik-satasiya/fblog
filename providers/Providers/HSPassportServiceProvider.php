<?php namespace HS\Providers;


use HS\Services\HSPassport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

class HSPassportServiceProvider extends ServiceProvider
{
     /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->app->singleton(Connection::class, function() {
        //     return $this->app['db.connection'];
        // });

        /*if ($this->app->runningInConsole()) {
            $this->commands([
                Purge::class
            ]);
        }*/

        // HsPassport::routes($this->app, ['prefix' => 'v1/oauth']);
    }
    /**
     * @return void
     */
    public function register()
    {
        HSPassport::routes();
    }
}
