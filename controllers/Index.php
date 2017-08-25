<?php
namespace HS\Controllers;


use Flash;
use BackendMenu;
use Response;

use HS\Controllers\BaseController;

class Index extends BaseController
{

    public $implement = [ ];
    public function __construct()
    {
    	// assets correction
    	$this->assetPath = '/assets';
        parent::__construct();
        $this->bodyClass = 'normal-container';
        // BackendMenu::setContext('index','index','test');
    }

    public function index()
    {

        // otehr code
        $this->addJs('js/main.js');
    }
}
