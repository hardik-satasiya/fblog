<?php namespace HS\Services;

use Laravel\Passport\Passport;
use DateTimeInterface;
use DateInterval;
use Carbon\Carbon;
use Route;

class HSPassport
{
    /**
     * Allow simultaneous logins for users
     *
     * @var bool
     */
    public static $allowMultipleTokens = false;
    /**
     * The date when access tokens expire (specific per password client).
     *
     * @var array
     */
    public static $tokensExpireAt = [];
    /**
     * Instruct Passport to keep revoked tokens pruned.
     */
    public static function allowMultipleTokens()
    {
        static::$allowMultipleTokens = true;
    }
    /**
     * Delete older tokens or just mark them as revoked?
     */
    public static function prunePreviousTokens()
    {
        Passport::pruneRevokedTokens();
    }
    /**
     * Get or set when access tokens expire.
     *
     * @param  \DateTimeInterface|null  $date
     * @param int $clientId
     * @return \DateInterval|static
     */
    public static function tokensExpireIn(DateTimeInterface $date = null, $clientId = null)
    {
        if (! $clientId) return Passport::tokensExpireIn($date);
        if (is_null($date)) {
            return isset(static::$tokensExpireAt[$clientId])
                ? Carbon::now()->diff(static::$tokensExpireAt[$clientId])
                : Passport::tokensExpireIn();
        } else {
            static::$tokensExpireAt[$clientId] = $date;
        }
        return new static;
    }
    /**
     * Get a Passport route registrar.
     *
     * @param  array  $options
     * @return RouteRegistrar
     */
    public static function routes($callback = null, array $options = [])
    {

         // strickly do not allow backend to anyone without main backend controller
        Route::group(['prefix'=>'v1/oauth'], function () {
            $routes = new HSPassportRouteRegistrar();
            $routes->all();
        });

        /*$callback = $callback ?: function ($router) {
            $router->all();
        };
        $defaultOptions = [
            'prefix' => 'oauth',

        ];
        $options = array_merge($defaultOptions, $options);
        echo "<pre/>";print_r($callback);exit();
        $callback->group($options, function ($router) use ($callback) {
            $routes = new HSPassportRouteRegistrar($router);
            $routes->all();
        });*/
    }
}