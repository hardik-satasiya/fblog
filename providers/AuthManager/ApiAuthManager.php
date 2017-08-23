<?php namespace HS\AuthManager;

use October\Rain\Auth\Manager as RainAuthManager;
use HS\Models\User\Settings as UserSettings;
use HS\Models\User\UserGroup as UserGroupModel;
use Illuminate\Auth\AuthManager;
use October\Rain\Auth\AuthException;

class ApiAuthManager extends AuthManager
{
    /**
     * Create a new Auth manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent:: __construct($app);
    }

    public function init()
    {

    }

}
