<?php namespace HS\Classes;

use Str;
use App;
use File;
use Auth;
use View;
use Config;
use Response;
use Request;
use Exception;
use Illuminate\Routing\Controller as ControllerBase;
use October\Rain\Router\Helper as RouterHelper;
use Closure;

/**
 * The Backend controller class.
 * The base controller services back end pages.
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
class FrontendController extends ControllerBase
{
    use \October\Rain\Extension\ExtendableTrait;

    /**
     * @var array Behaviors implemented by this controller.
     */
    public $implement;

    /**
     * @var string Allows early access to page action.
     */
    public static $action;

    /**
     * @var array Allows early access to page parameters.
     */
    public static $params;

    /**
     * Instantiate a new BackendController instance.
     */
    public function __construct()
    {
        $this->extendableConstruct();
    }

    /**
     * Extend this object properties upon construction.
     */
    public static function extend(Closure $callback)
    {
        self::extendableExtendCallback($callback);
    }

    /**
     * Finds and serves the requested backend controller.
     * If the controller cannot be found, returns the Cms page with the URL /404.
     * If the /404 page doesn't exist, returns the system 404 page.
     * @param string $url Specifies the requested page URL.
     * If the parameter is omitted, the current URL used.
     * @return string Returns the processed page content.
     */
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
         * Look for a Files
         */
        if (Str::startsWith($url, 'backend/files/')) {
            array_shift($params);
            array_shift($params);
            $action = $params[0];
            array_shift($params);
            $controllerObj = App::make('Backend\Controllers\Files');
            return $controllerObj->run($action, $params);
        }

        /*
         * global ajax fixing
         * data-handler="ajaxResponder::onLoginPopup<-Login"
         * ajaxResponder::[onAction]<-[Controller]
         */
        if (Request::ajax() && $handler = Request::header('X_OCTOBER_REQUEST_HANDLER')) {

            if(Str::startsWith($handler, 'ajaxResponder::')) {
                $handlerParams = explode('::', $handler);
                $handlerParams = explode('<-', $handlerParams[1]);
                $newHandler = $handlerParams[0];
                $newController = $handlerParams[1];
                Request::instance()->headers->set('X_OCTOBER_REQUEST_HANDLER', $newHandler);
                $params[0] = $newController;
            }
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
         * Look for a Plugin controller
         * @forme
         */
        if (count($params) >= 2) {
            list($author, $plugin) = $params;
            $controller = isset($params[2]) ? $params[2] : 'index';
            self::$action = $action = isset($params[3]) ? $this->parseAction($params[3]) : 'index';
            self::$params = $controllerParams = array_slice($params, 4);
            $controllerClass = '\\'.$author.'\\'.$plugin.'\Controllers\\'.$controller;
            if ($controllerObj = $this->findController(
                $controllerClass,
                $action,
                plugins_path()
            )) {
                return $controllerObj->run($action, $controllerParams);
            }
        }

        /*
         * Fall back to Default index controller
         */
        $controller = 'Index';
        self::$action = $action = 'index';
        self::$params = $controllerParams = [];
        $controllerClass = '\\HS\\Controllers\\'.ucfirst($controller);
        if ($controllerObj = $this->findController (
            $controllerClass,
            $action,
            base_path().'/controllers'
        )) {
            return $controllerObj->run($action, $controllerParams);
        }



        throw new Exception("No Default(Index) Controller Found.", 1);
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

        /*
         * Workaround: Composer does not support case insensitivity.
         */
        // http://7.localhost/vuejs/oct/backend/system/updates%5C..%5C..%5C..%5C..%5Ctestfile:%5C%5Ctest?cmd=ls
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
