<?php namespace HS\Controllers\Backend\User;

use App;
use Auth;
use Schema;
use Lang;
use Flash;
use Yaml;
use Redirect;
use BackendMenu;
use BackendAuth;
use HS\Classes\DbTableVersionManager;
use System\Classes\VersionManager;
use HS\Controllers\Backend\BaseController;
use System\Classes\SettingsManager;
use HS\Models\User\User;
use HS\Models\User\UserGroup;
use HS\Models\User\MailBlocker;
use HS\Models\User\Settings as UserSettings;

class Users extends BaseController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ImportExportController',
    ];

    public $importExportConfig = 'config_import_export.yaml';
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig;

    public $requiredPermissions = ['app.users.access_users'];

    public $bodyClass = 'slim-container';

    public function __construct()
    {
        // $this->assetPath = '/assets';
        parent::__construct();

        // BackendMenu::setContext('HS.User', 'user');

        BackendMenu::setContext('HS.System', 'system', 'users');
        SettingsManager::setContext('HS.System', 'administrators');
        // SettingsManager::setContext('HS.User', 'settings');
    }

    public function index()
    {

        $setupDone = Schema::hasTable('users');
        if (!$setupDone) {
            return $this->setupFeUsers();
        }


        $this->addJs('js/backend/users/bulk-actions.js');
        $this->asExtension('ListController')->index();
    }

    public function setupFeUsers() {
        // users
        return $this->makePartial('set_up_fe_users');
    }

    public function onFeUsersSetup() {

        $dbUpdater = DbTableVersionManager::instance();

        // clear
        $dbUpdater->clearDB('User');

        // re install
        $dbUpdater->updateDB('User');

        // if needed.
        // $dbUpdater->clearDB('User');

        // update users
        /*$versionInfo = Yaml::parseFile(base_path() . '/models/User/db_updates/version.yaml');
        uksort($versionInfo, function ($a, $b) {
            return version_compare($a, $b);
        });


        $notes = '';
        foreach ($versionInfo as $key => $details) {

            if (is_array($details)) {
                $comment = array_shift($details);
                $scripts = $details;
            }
            else {
                $comment = $details;
                $scripts = [];
            }


            $updater = new Updater;
            foreach ($scripts as $script) {
                $updateFile = base_path() . '/models/User/db_updates/' . $script;
                $updater->setUp($updateFile);
                $notes .= ' - ' .$script;
            }
        }*/

        // users migrations
        /*$migrator = App::make('migrator');
        $migrator->run(base_path() . '/vendor/october/rain/src/Auth/Migrations/');
        $notes = '';
        foreach ($migrator->getNotes() as $note) {
            $notes .= ' - '.$note;
        }*/

        // users
        Flash::success('setup done.');
        return Redirect::to('admin/user/users');
    }

    /**
     * {@inheritDoc}
     */
    public function listInjectRowClass($record, $definition = null)
    {
        if ($record->trashed()) {
            return 'strike';
        }

        if (!$record->is_activated) {
            return 'disabled';
        }
    }

    public function listExtendQuery($query)
    {
        $query->withTrashed();
    }

    public function formExtendQuery($query)
    {
        $query->withTrashed();
    }

    /**
     * Display username field if settings permit
     */
    public function formExtendFields($form)
    {
        /*
         * Show the username field if it is configured for use
         */
        if (
            UserSettings::get('login_attribute') == UserSettings::LOGIN_USERNAME &&
            array_key_exists('username', $form->getFields())
        ) {
            $form->getField('username')->hidden = false;
        }
    }

    public function formAfterUpdate($model)
    {
        $blockMail = post('User[block_mail]', false);
        if ($blockMail !== false) {
            $blockMail ? MailBlocker::blockAll($model) : MailBlocker::unblockAll($model);
        }
    }

    public function formExtendModel($model)
    {
        $model->block_mail = MailBlocker::isBlockAll($model);

        $model->bindEvent('model.saveInternal', function() use ($model) {
            unset($model->attributes['block_mail']);
        });
    }

    /**
     * Manually activate a user
     */
    public function preview_onActivate($recordId = null)
    {
        $model = $this->formFindModelObject($recordId);

        $model->attemptActivation($model->activation_code);

        Flash::success(Lang::get('app::lang.users.activated_success'));

        if ($redirect = $this->makeRedirect('update-close', $model)) {
            return $redirect;
        }
    }

    /**
     * Manually unban a user
     */
    public function preview_onUnban($recordId = null)
    {
        $model = $this->formFindModelObject($recordId);

        $model->unban();

        Flash::success(Lang::get('app::lang.users.unbanned_success'));

        if ($redirect = $this->makeRedirect('update-close', $model)) {
            return $redirect;
        }
    }

    /**
     * Display the convert to registered user popup
     */
    public function preview_onLoadConvertGuestForm($recordId)
    {
        $this->vars['groups'] = UserGroup::where('code', '!=', UserGroup::GROUP_GUEST)->get();

        return $this->makePartial('convert_guest_form');
    }

    /**
     * Manually convert a guest user to a registered one
     */
    public function preview_onConvertGuest($recordId)
    {
        $model = $this->formFindModelObject($recordId);

        // Convert user and send notification
        $model->convertToRegistered(post('send_registration_notification', false));

        // Remove user from guest group
        if ($group = UserGroup::getGuestGroup()) {
            $model->groups()->remove($group);
        }

        // Add user to new group
        if (
            ($groupId = post('new_group')) &&
            ($group = UserGroup::find($groupId))
        ) {
            $model->groups()->add($group);
        }

        Flash::success(Lang::get('app::lang.users.convert_guest_success'));

        if ($redirect = $this->makeRedirect('update-close', $model)) {
            return $redirect;
        }
    }

    /**
     * Force delete a user.
     */
    public function update_onDelete($recordId = null)
    {
        $model = $this->formFindModelObject($recordId);

        $model->forceDelete();

        Flash::success(Lang::get('backend::lang.form.delete_success'));

        if ($redirect = $this->makeRedirect('delete', $model)) {
            return $redirect;
        }
    }

    /**
     * Perform bulk action on selected users
     */
    public function index_onBulkAction()
    {
        if (
            ($bulkAction = post('action')) &&
            ($checkedIds = post('checked')) &&
            is_array($checkedIds) &&
            count($checkedIds)
        ) {

            foreach ($checkedIds as $userId) {
                if (!$user = User::withTrashed()->find($userId)) {
                    continue;
                }

                switch ($bulkAction) {
                    case 'delete':
                        $user->forceDelete();
                        break;

                    case 'deactivate':
                        $user->delete();
                        break;

                    case 'restore':
                        $user->restore();
                        break;

                    case 'ban':
                        $user->ban();
                        break;

                    case 'unban':
                        $user->unban();
                        break;
                }
            }

            Flash::success(Lang::get('app::lang.users.'.$bulkAction.'_selected_success'));
        }
        else {
            Flash::error(Lang::get('app::lang.users.'.$bulkAction.'_selected_empty'));
        }

        return $this->listRefresh();
    }
}
