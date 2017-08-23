<?php namespace HS\Services;

use Route;

class HSPassportRouteRegistrar
{
    /**
     * Application
     */
    private $app;
    /**
     * Create a new route registrar instance.
     *
     * @param  $app
     * @return void
     */
    public function __construct()
    {
        // $this->app = $app;
    }
    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forAccessTokens();
        $this->forTransientTokens();
        $this->forClients();
        $this->forPersonalAccessTokens();
    }
    /**
     * Register the routes for retrieving and issuing access tokens.
     *
     * @return void
     */
    public function forAccessTokens()
    {
        // Illuminate\Auth\AuthManager

        Route::group(['namespace' => '\HS\Controllers\Backend'], function() {
            Route::post('/token', 'AccessToken@issueToken');
            // Route::get('/token', 'AccessToken@issueToken');
            // Route::post('/token', function() {
            //     return ['new'];
            // });
        });

        Route::group(['middleware' => ['auth'], 'namespace' => '\Laravel\Passport\Http\Controllers'], function () {
            Route::get('/tokens', [
                'uses' => 'AuthorizedAccessTokenController@forUser',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::delete('/tokens/{token_id}', [
                'uses' => 'AuthorizedAccessTokenController@destroy',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
        });
    }
    /**
     * Register the routes needed for refreshing transient tokens.
     *
     * @return void
     */
    public function forTransientTokens()
    {
        Route::post('/token/refresh', [
            'middleware' => ['auth'],
            'uses' => 'TransientTokenController@refresh',
            'namespace' => '\Laravel\Passport\Http\Controllers'
        ]);
    }
    /**
     * Register the routes needed for managing clients.
     *
     * @return void
     */
    public function forClients()
    {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/clients', [
                'uses' => 'ClientController@forUser',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::post('/clients', [
                'uses' => 'ClientController@store',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::put('/clients/{client_id}', [
                'uses' => 'ClientController@update',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::delete('/clients/{client_id}', [
                'uses' => 'ClientController@destroy',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
        });
    }
    /**
     * Register the routes needed for managing personal access tokens.
     *
     * @return void
     */
    public function forPersonalAccessTokens()
    {
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/scopes', [
                'uses' => 'ScopeController@all',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::get('/personal-access-tokens', [
                'uses' => 'PersonalAccessTokenController@forUser',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::post('/personal-access-tokens', [
                'uses' => 'PersonalAccessTokenController@store',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
            Route::delete('/personal-access-tokens/{token_id}', [
                'uses' => 'PersonalAccessTokenController@destroy',
                'namespace' => '\Laravel\Passport\Http\Controllers'
            ]);
        });
    }
}