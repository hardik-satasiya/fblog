<?php namespace HS\Controllers\Backend\User;

use Flash;
use BackendMenu;
use HS\Controllers\Backend\BaseController;
use System\Classes\SettingsManager;

/**
 * User Groups Back-end Controller
 */
class UserGroups extends BaseController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['app.users.access_groups'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('HS.System', 'system', 'usergroups');
        SettingsManager::setContext('HS.System', 'administrators');
    }
}
