<?php
namespace HS\Controllers\Backend;

use Backend;
use Flash;
use BackendMenu;
use Response;

use HS\Controllers\Backend\BaseController;

class Fashion extends BaseController
{
    public $implement = [ ];
    public function __construct()
    {
        // assets correction
        $this->assetPath = '/assets';
        parent::__construct();
        BackendMenu::setContext('HS.Fashion','items');

        BackendMenu::registerCallback(function ($manager) {
            // dd($manager); //uncomment to explore the current menu items
            $manager->addSideMenuItem('HS.Fashion', 'items', 'add', [
                'label'       => 'Add Items',
                'icon'        => 'icon-puzzle-piece',
                'url'         => Backend::backendUrl('admin/fashion/add'),
                'permissions' => ['my.plugin.access_page'],
                'order'         => 550
            ]);
        });

    }

    public function index()
    {
        // otehr code
        // $this->addJs('js/main.js');
        // $this->addCss('css/main.css');
        // $this->bodyClass = 'compact-container';
    }

    public function add()
    {
        BackendMenu::setContext('HS.Fashion','items', 'add');
        // otehr code
        // $this->addJs('js/main.js');
        // $this->addCss('css/main.css');
        // $this->bodyClass = 'compact-container';
    }
}
