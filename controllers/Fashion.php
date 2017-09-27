<?php
namespace HS\Controllers;


use Flash;
use BackendMenu;
use Response;

use HS\Controllers\BaseController;
use HS\Models\Fashion\FashionMaster;

class Fashion extends BaseController
{

    public $implement = [ ];
    public function __construct()
    {
        parent::__construct();

        // assets correction
        $this->assetPath = '/assets';

        $this->addJs('js/fashion.js');
        $this->bodyClass = 'normal-container';
        // BackendMenu::setContext('index','index','test');
    }

    public function index($slug)
    {

        $this->vars['item'] = FashionMaster::where('slug', $slug)->first();

        // otehr code
        $this->addCss('css/details-page.css');
        //
    }
}
