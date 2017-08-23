<?php namespace HS\Controllers\Backend;

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

use Illuminate\Routing\Controller as BaseController;
use October\Rain\Router\Helper as RouterHelper;

class Api extends BaseController
{
    public $implement = [];

    /**
     * @var string Allows early access to page action.
     */
    public static $action;

    /**
     * @var array Allows early access to page parameters.
     */
    public static $params;


    public function run($url = null)
    {
        $params = RouterHelper::segmentizeUrl($url);

        /*
         * Database check
         */
        if (!App::hasDatabase()) {
            return Config::get('app.debug', false)
                ? Response::make(View::make('backend::no_database'), 200)
                : App::make('Cms\Classes\Controller')->run($url);
        }

        /*
         * Look for a Main controllers
         */
        $innerControllerFound = false;
        $paramClone = $params;
        if (count($paramClone) > 1) {
            $counterClassEmel = count($paramClone) - 1;
            array_walk($paramClone, function (&$value, $index) {
                $value = Str::studly($value);
            });
            for ($i = $counterClassEmel; $i >= 0; $i--) {
                $predicatedClass = '\\HS\\Controllers\\' . Str::studly(implode('\\', $paramClone));
                // $predicatedClass . '\Index';
                $predicatedPath = implode('/', $paramClone);
                $predicatedPath = base_path() . '/controllers/' . $predicatedPath;

                // @to solve url issues with camlisation
                if ($controllerFile = File::existsInsensitive($predicatedPath . '.php')) {
                    include_once($controllerFile);
                }

                if (class_exists($predicatedClass)) {
                    $predicatedControllerParams = array_slice($params, $i + 1);
                    $predicatedAction = array_shift($predicatedControllerParams);
                    if (empty($predicatedAction)) {
                        $predicatedAction = 'index';
                    }
                    $innerControllerFound = true;
                    break;
                }
                unset($paramClone[$i]);
            }
        }


        /*
         * Execute
         */
        $controllerClassPath = base_path().'/controllers';
        if ($innerControllerFound) {
            $controller = $predicatedClass;
            self::$action = $action = $predicatedAction;
            self::$params = $controllerParams = $predicatedControllerParams;
            $controllerClass = $controller;
            $controllerClassPath = $predicatedPath;
        } else {
            $controller = isset($params[0]) ? $params[0] : '';
            self::$action = $action = isset($params[1]) ? $this->parseAction($params[1]) : 'index';
            self::$params = $controllerParams = array_slice($params, 2);
            $controllerClass = '\\HS\\Controllers\\'. ucfirst($controller);
        }

        if ($controllerObj = $this->findController(
            $controllerClass,
            $action,
            $controllerClassPath
        )) {
            return $controllerObj->run($action, $controllerParams);
        }


        /*
         * Fall back to Default index controller
         */
        /*$controller = 'Index';
        self::$action = $action = 'index';
        self::$params = $controllerParams = [];
        $controllerClass = '\\HS\\Controllers\\'.ucfirst($controller);
        if ($controllerObj = $this->findController (
            $controllerClass,
            $action,
            base_path().'/controllers'
        )) {
            return $controllerObj->run($action, $controllerParams);
        }*/


        throw new Exception("No Route Found. {$controllerClass}Api->{$action}()", 1);
    }

    /**
     * This method is used internally.
     * Finds a backend controller with a callable action method.
     * @param string $controller Specifies a method name to execute.
     * @param string $action Specifies a method name to execute.
     * @param string $inPath Base path for class file location.
     * @return ControllerBase Returns the backend controller object
     */
    protected function findController($controller, $action, $inPath)
    {

        // for api usage
        $controller = $controller . 'Api';
        /*
         * Workaround: Composer does not support case insensitivity.
         */
        if (!class_exists($controller)) {
            $controller = Str::normalizeClassName($controller);
            $controllerFile = $inPath.strtolower(str_replace('\\', '/', $controller)) . '.php';
            if ($controllerFile = File::existsInsensitive($controllerFile)) {
                include_once($controllerFile);
            }
        }
        if (!class_exists($controller)) {
            return false;
        }

        $controllerObj = App::make($controller);

        if ($controllerObj->actionExists($action)) {
            return $controllerObj;
        }

        return false;
    }

    /**
     * Process the action name, since dashes are not supported in PHP methods.
     * @param  string $actionName
     * @return string
     */
    protected function parseAction($actionName)
    {
        if (strpos($actionName, '-') !== false) {
            return camel_case($actionName);
        }

        return $actionName;
    }
}
