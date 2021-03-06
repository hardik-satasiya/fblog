<?php namespace HS\Controllers\Backend;

use Mail;
use Config;
use Flash;
use Backend;
use Validator;
use BackendAuth;
use Backend\Models\AccessLog;
use HS\Controllers\Backend\BaseController;
use System\Classes\UpdateManager;
use ApplicationException;
use ValidationException;
use Exception;

/**
 * Authentication controller
 *
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class Auth extends BaseController
{
    protected $publicActions = ['index', 'signin', 'signout', 'restore', 'reset'];

    public function __construct()
    {
        parent::__construct();
        $this->layout = 'be-auth';
    }

    /**
     * Default route, redirects to signin.
     */
    public function index()
    {
        return Backend::redirect( Config::get('hs.backendUri', 'admin') . '/auth/signin');
    }

    /**
     * Displays the log in page.
     */
    public function signin()
    {
        $this->bodyClass = 'signin';

        try {
            if (post('postback')) {
                return $this->signin_onSubmit();
            }
            else {
                $this->bodyClass .= ' preload';
            }
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }
    }

    public function signin_onSubmit()
    {
        $rules = [
            'login'    => 'required|between:2,255',
            'password' => 'required|between:4,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        // Authenticate user
        $user = BackendAuth::authenticate([
            'login' => post('login'),
            'password' => post('password')
        ], true);

        // Redirect to the intended page after successful sign in
        return Backend::redirectIntended(Config::get('hs.backendUri', 'admin'));
    }

    /**
     * Logs out a backend user.
     */
    public function signout()
    {
        BackendAuth::logout();
        return Backend::redirect(Config::get('hs.backendUri', 'admin'));
    }

    /**
     * Request a password reset verification code.
     */
    public function restore()
    {
        try {
            if (post('postback')) {
                return $this->restore_onSubmit();
            }
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }
    }

    public function restore_onSubmit()
    {
        $rules = [
            'login' => 'required|between:2,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $user = BackendAuth::findUserByLogin(post('login'));
        if (!$user) {
            throw new ValidationException([
                'login' => trans('backend::lang.account.restore_error', ['login' => post('login')])
            ]);
        }

        Flash::success(trans('backend::lang.account.restore_success'));

        $code = $user->getResetPasswordCode();
        $link = Backend::url(Config::get('hs.backendUri', 'admin') . '/auth/reset/'.$user->id.'/'.$code);

        $data = [
            'name' => $user->full_name,
            'link' => $link,
        ];

        Mail::send('backend::mail.restore', $data, function ($message) use ($user) {
            $message->to($user->email, $user->full_name)->subject(trans('backend::lang.account.password_reset'));
        });

        return Backend::redirect(Config::get('hs.backendUri', 'admin') . '/auth/signin');
    }

    /**
     * Reset backend user password using verification code.
     */
    public function reset($userId = null, $code = null)
    {
        try {
            if (post('postback')) {
                return $this->reset_onSubmit();
            }

            if (!$userId || !$code) {
                throw new ApplicationException(trans('backend::lang.account.reset_error'));
            }
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }

        $this->vars['code'] = $code;
        $this->vars['id'] = $userId;
    }

    public function reset_onSubmit()
    {
        if (!post('id') || !post('code')) {
            throw new ApplicationException(trans('backend::lang.account.reset_error'));
        }

        $rules = [
            'password' => 'required|between:4,255'
        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $code = post('code');
        $user = BackendAuth::findUserById(post('id'));

        if (!$user->checkResetPasswordCode($code)) {
            throw new ApplicationException(trans('backend::lang.account.reset_error'));
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            throw new ApplicationException(trans('backend::lang.account.reset_fail'));
        }

        $user->clearResetPassword();

        Flash::success(trans('backend::lang.account.reset_success'));

        return Backend::redirect(Config::get('hs.backendUri', 'admin') . '/auth/signin');
    }
}
