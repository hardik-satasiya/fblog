<?php
namespace HS\Controllers\Backend;


use Flash;
use BackendMenu;

use HS\Controllers\Backend\BaseController;

class Index extends BaseController
{

    public $implement = [ ];



    public function __construct()
    {
    	// assets correction
    	$this->assetPath = '/assets';

        parent::__construct();

        BackendMenu::setContext('HS.Backend','dashboard');
    }

    public function index()
    {

    	$this->addJs('js/main.js');
        $this->addCss('css/main.css');

        // $this->bodyClass = 'compact-container';
    }
}
