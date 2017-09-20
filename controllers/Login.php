<?php
namespace HS\Controllers;

use Route;
use FeAuth;
use Flash;
use Event;
use BackendMenu;
use Redirect;
use Validator;
use Request;
use Lang;
use Mail;
use GuzzleHttp;
use Response;
use Exception;

use ValidationException;
use ApplicationException;

use HS\Models\Lab\Mytable;
use HS\Controllers\BaseController;
use HS\Models\User\Settings as UserSettings;

class Login extends BaseController
{
    public $implement = [ ];

    protected $pulbicActions = ['logout'];

    protected $allowGroup = 'guest';

    public function __construct()
    {
        parent::__construct();
        // BackendMenu::setContext('index','index','test');
    }

    public function loginAttribute() {

        return UserSettings::get('login_attribute', UserSettings::LOGIN_EMAIL);
    }

    public function index()
    {
        $user = FeAuth::getUser();
        $this->vars['user'] = $user;
        $this->vars['loginAttributeLabel'] = $this->loginAttribute();
    }

    public function settings()
    {
        return Redirect::to('login/index');
    }

    /**
     * Sign in the user
     */
    public function onLoginPopup()
    {
        sleep(2);
        return $this->makePartial('loginpopup');
    }

    /**
     * Sign in the user
     */
    public function onSignin()
    {
        try {
            /*
             * Validate input
             */
            $data = post();
            $rules = [];

            $rules['login'] = $this->loginAttribute() == UserSettings::LOGIN_USERNAME
                ? 'required|between:2,255'
                : 'required|email|between:6,255';

            $rules['password'] = 'required|between:4,255';

            if (!array_key_exists('login', $data)) {
                $data['login'] = post('username', post('email'));
            }

            $validation = Validator::make($data, $rules);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            /*
             * Authenticate user
             */
            $credentials = [
                'login'    => array_get($data, 'login'),
                'password' => array_get($data, 'password')
            ];

            Event::fire('app.user.beforeAuthenticate', [$this, $credentials]);

            try {
                $user = FeAuth::authenticate($credentials, true);
            }
            catch(Exception $ex) {
                throw new ValidationException([$ex->getMessage()]);
                // Flash::error($ex->getMessage());
                // return Redirect::to('login/index');
            }
            /*
             * Redirect to the intended page after successful sign in
             */
            $redirectUrl = 'index';
            if ($redirectUrl = input('redirect', $redirectUrl)) {
                return Redirect::intended($redirectUrl);
            }
        } catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;

            } else {
                Flash::error($ex->getMessage());
            }
        }
    }

    /**
     * Register the user
     */
    public function onRegister()
    {
        try {
            if (!UserSettings::get('allow_registration', true)) {
                throw new ApplicationException(Lang::get('app::lang.account.registration_disabled'));
            }

            /*
             * Validate input
             */
            $data = post();

            if (!array_key_exists('password_confirmation', $data)) {
                $data['password_confirmation'] = post('password');
            }

            $rules = [
                'name'    => 'required',
                'email'    => 'required|email|between:6,255',
                'password' => 'required|between:4,255|confirmed'
            ];

            if ($this->loginAttribute() == UserSettings::LOGIN_USERNAME) {
                $rules['username'] = 'required|between:2,255';
            }

            $validation = Validator::make($data, $rules);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            /*
             * Register user
             */
            $requireActivation = UserSettings::get('require_activation', true);
            $automaticActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_AUTO;
            $userActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_USER;
            $user = FeAuth::register($data, $automaticActivation);

            /*
             * Activation is by the user, send the email
             */
            if ($userActivation) {
                $this->sendActivationEmail($user);
                Flash::success(Lang::get('app::lang.account.activation_email_sent'));
            }

            /*
             * Automatically activated or not required, log the user in
             */
            if ($automaticActivation || !$requireActivation) {
                FeAuth::login($user);
            }

            if ($redirectUrl = post('redirect', 'index')) {
                return Redirect::intended($redirectUrl);
            }

            return Redirect::intended('index');

        } catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            } else {
                Flash::error($ex->getMessage());
            }
        }
    }

    /**
     * Log out the user
     *
     * Usage:
     *   <a data-request="onLogout">Sign out</a>
     *
     * With the optional redirect parameter:
     *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
     *
     */
    public function logout()
    {
        $user = FeAuth::getUser();

        FeAuth::logout();

        if ($user) {
            Event::fire('app.user.logout', [$user]);
        }

        $url = post('redirect', 'index');
        Flash::success(Lang::get('app::lang.session.logout'));

        return Redirect::to($url);
    }

    /**
     * Log out the user
     *
     * Usage:
     *   <a data-request="onLogout">Sign out</a>
     *
     * With the optional redirect parameter:
     *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
     *
     */
    public function onLogout()
    {
        $user = FeAuth::getUser();

        FeAuth::logout();

        if ($user) {
            Event::fire('app.user.logout', [$user]);
        }

        $url = post('redirect', Request::fullUrl());
        Flash::success(Lang::get('app::lang.session.logout'));

        return Redirect::to($url);
    }


    /**
     * Activate the user
     * @param  string $code Activation code
     */
    public function onActivate($code = null)
    {
        try {
            $code = post('code', $code);

            /*
             * Break up the code parts
             */
            $parts = explode('!', $code);
            if (count($parts) != 2) {
                throw new ValidationException(['code' => Lang::get('app::lang.account.invalid_activation_code')]);
            }

            list($userId, $code) = $parts;

            if (!strlen(trim($userId)) || !($user = FeAuth::findUserById($userId))) {
                throw new ApplicationException(Lang::get('app::lang.account.invalid_user'));
            }

            if (!$user->attemptActivation($code)) {
                throw new ValidationException(['code' => Lang::get('app::lang.account.invalid_activation_code')]);
            }

            Flash::success(Lang::get('app::lang.account.success_activation'));

            /*
             * Sign in the user
             */
            FeAuth::login($user);
        } catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            } else {
                Flash::error($ex->getMessage());
            }
        }
    }

    /**
     * Update the user
     */
    public function onUpdate()
    {
        if (!$user = FeAuth::getUser()) {
            return;
        }

        $user->fill(post());
        $user->save();

        /*
         * Password has changed, reauthenticate the user
         */
        if (strlen(post('password'))) {
            FeAuth::login($user->reload(), true);
        }

        Flash::success(post('flash', Lang::get('app::lang.account.success_saved')));

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    /**
     * Deactivate user
     */
    public function onDeactivate()
    {
        if (!$user = FeAuth::getUser()) {
            return;
        }

        if (!$user->checkHashValue('password', post('password'))) {
            throw new ValidationException(['password' => Lang::get('app::lang.account.invalid_deactivation_pass')]);
        }

        $user->delete();
        FeAuth::logout();

        Flash::success(post('flash', Lang::get('app::lang.account.success_deactivation')));

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    /**
     * Trigger a subsequent activation email
     */
    public function onSendActivationEmail()
    {
        try {
            if (!$user = FeAuth::getUser()) {
                throw new ApplicationException(Lang::get('app::lang.account.login_first'));
            }

            if ($user->is_activated) {
                throw new ApplicationException(Lang::get('app::lang.account.already_active'));
            }

            Flash::success(Lang::get('app::lang.account.activation_email_sent'));

            $this->sendActivationEmail($user);
        } catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            } else {
                Flash::error($ex->getMessage());
            }
        }

        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }
    }

    /**
     * Sends the activation email to a user
     * @param  User $user
     * @return void
     */
    protected function sendActivationEmail($user)
    {
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        $link = $this->currentPageUrl([
            $this->property('paramCode') => $code
        ]);

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        Mail::send('app::mail.user.activate', $data, function ($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }

    /**
     * Trigger the password reset email
     */
    public function onRestorePassword()
    {
        $rules = [
            'email' => 'required|email|between:6,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if (!$user = UserModel::findByEmail(post('email'))) {
            throw new ApplicationException(trans('app::lang.account.invalid_user'));
        }

        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        $link = $this->controller->currentPageUrl([
            $this->property('paramCode') => $code
        ]);

        $data = [
            'name' => $user->name,
            'link' => $link,
            'code' => $code
        ];

        Mail::send('app::mail.user.restore', $data, function($message) use ($user) {
            $message->to($user->email, $user->full_name);
        });
    }

    /**
     * Perform the password reset
     */
    public function onResetPassword()
    {
        $rules = [
            'code'     => 'required',
            'password' => 'required|between:4,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        /*
         * Break up the code parts
         */
        $parts = explode('!', post('code'));
        if (count($parts) != 2) {
            throw new ValidationException(['code' => trans('app::lang.account.invalid_activation_code')]);
        }

        list($userId, $code) = $parts;

        if (!strlen(trim($userId)) || !($user = FeAuth::findUserById($userId))) {
            throw new ApplicationException(trans('app::lang.account.invalid_user'));
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            throw new ValidationException(['code' => trans('app::lang.account.invalid_activation_code')]);
        }
    }

    /**
     * Redirect to the intended page after successful update, sign in or registration.
     * The URL can come from the "redirect" property or the "redirect" postback value.
     * @return mixed
     */
    protected function makeRedirection()
    {
        $redirectUrl = url()->current();

        if ($redirectUrl = post('redirect', $redirectUrl)) {
            return Redirect::to($redirectUrl);
        }
    }

    /**
     * Checks if the force secure property is enabled and if so
     * returns a redirect object.
     * @return mixed
     */
    protected function redirectForceSecure()
    {
        if (Request::secure() ||
            Request::ajax() ||
            !$this->property('forceSecure')
        ) {
            return;
        }

        return Redirect::secure(Request::path());
    }
}
