<?php
namespace HS\Controllers\Backend;

use Backend;
use Flash;
use Schema;
use BackendMenu;
use Response;

use HS\Classes\DbTableVersionManager;
use HS\Controllers\Backend\BaseController;

class Fashion extends BaseController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        // 'Backend.Behaviors.ImportExportController',
    ];

    // public $importExportConfig = 'config_import_export.yaml';
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig;

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
        $setupDone = Schema::hasTable('fashion_master');
        if (!$setupDone) {
            return $this->setupFashion();
        }

        // $this->addJs('js/backend/users/bulk-actions.js');
        $this->asExtension('ListController')->index();

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

    public function setupFashion() {

        return $this->makePartial('set_up_fashion');
    }

    public function onSetupFashion() {

        $dbUpdater = DbTableVersionManager::instance();

        // clear
        // $dbUpdater->clearDB('Fashion');

        // re install
        $dbUpdater->updateDB('Fashion');

        $notes = $dbUpdater->getNotes();

        // users
        Flash::success('setup done.' . implode(', ', $notes));

        return true;
    }
}
