<?php namespace HS\Classes;

use App;
use Backend\Classes\WidgetBase;

/*
 * use : ajaxResponder::onLoginPopup__Login
 *                      action__controller <- due to regx rules
 */
class AjaxResponderWidget extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'ajaxResponder';

    protected $callableController = 'index';

    protected $action = 'index';

    protected $delimiterForActionSeperator = '__';

    protected $controllerObj = null;


    public function methodExists($methodName)
    {
        $request = explode($this->delimiterForActionSeperator, $methodName);
        if(!count($request) > 1) {
            return false;
        }

        $this->callableController = $request[1];
        $this->action = $request[0];

        $controllerClass = '\\HS\\Controllers\\'. ucfirst($this->callableController);

        if (!class_exists($controllerClass)) {
            return false;
        }

        $this->controllerObj = App::make($controllerClass);
        if (!$this->controllerObj->actionExists($this->action)) {
            return false;
        }

        return true;
    }

    public function __call($methodName, $args) {

        if($methodName === $this->action . $this->delimiterForActionSeperator . $this->callableController) {
            return $this->controllerObj->run($this->action, $args);
        }

        return false;
    }
}
