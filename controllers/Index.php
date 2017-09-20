<?php
namespace HS\Controllers;


use Flash;
use BackendMenu;
use Response;

use HS\Controllers\BaseController;
use HS\Models\Fashion\FashionMaster;

class Index extends BaseController
{

    public $implement = [ ];
    public function __construct()
    {
    	// assets correction
    	$this->assetPath = '/assets';
        parent::__construct();
        $this->bodyClass = 'normal-container has-transparent-header';
        // BackendMenu::setContext('index','index','test');
    }

    public function index()
    {
        $this->vars['fashionItems'] = FashionMaster::orderBy('created_at', 'desc')->get()->all();
        // dd($this->vars['fashionItems']);

        // otehr code
        $this->addJs('third-party/cross-slider.js');
        $this->addCss('third-party/cross-slider.css');
        $this->addJs('js/fashion.js');

        //
    }
}
