<?php namespace HS\Controllers\Backend\Settings;

use BackendMenu;
use BackendAuth;
use HS\Controllers\Backend\BaseController as Controller;
use System\Classes\SettingsManager;

/**
 * Backend user groups controller
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class UserGroups extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['backend.manage_users'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('HS.System', 'system', 'users');
        SettingsManager::setContext('HS.System', 'administrators');
    }

    /**
     * Add available permission fields to the Group form.
     */
    public function formExtendFields($form)
    {
        /*
         * Add permissions tab
         */
        $form->addTabFields($this->generatePermissionsField());
    }

    /**
     * Adds the permissions editor widget to the form.
     * @return array
     */
    protected function generatePermissionsField()
    {
        return [
            'permissions' => [
                'tab' => 'backend::lang.user.permissions',
                'type' => 'Backend\FormWidgets\PermissionEditor',
                'mode' => 'checkbox'
            ]
        ];
    }
}
