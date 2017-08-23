<?php namespace HS\Controllers;

use Flash;
use BackendMenu;
use Auth;
use GuzzleHttp;
use Response;
use Request;
use App;
use Str;
use File;
use Exception;

use HS\Classes\ApiController as BaseController;
use October\Rain\Router\Helper as RouterHelper;

class IndexApi extends BaseController
{

    /**
     * @var array Defines a collection of actions available without authentication.
     */
    protected $publicActions = ['index'];

    public function index()
    {
        return 'index action indexAPI';
    }

}