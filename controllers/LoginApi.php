<?php namespace HS\Controllers;

use DB;
use ApiAuth;
use Flash;
use BackendMenu;
use Auth;
use GuzzleHttp;
use Response;
use Request;
use App;
use Str;
use File;
use Exception;

use Laravel\Passport\Token;
use Lcobucci\JWT\Parser as JwtParser;

use HS\Classes\ApiController as BaseController;
use October\Rain\Router\Helper as RouterHelper;

class LoginApi extends BaseController
{

    /**
     * @var array Defines a collection of actions available without authentication.
     */
    protected $publicActions = ['token', 'logout', 'checkauth', 'openaccess'];

    // /v1/api/login/openaccess/10/some-title
    public function openaccess($id = 0, $title = '')
    {
        return [$id, $title];
    }

    public function token()
    {
        // echo "<pre/>";print_r($_REQUEST);exit();
        $http = new GuzzleHttp\Client;
        $output = [];
        try {
            $response = $http->post('http://7.localhost/vuejs/my_frame_work_ready/my-framework-dist/v1/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'aCwGEGls55SjsWIGH4ug8AdTPHMQV5LQNqvL6Idy',
                    'username' => post('email'), // 'hardik@admin.com1',
                    'password' => post('password'), //'pass',
                    'scope' => '',
                ],
            ]);
        } catch (\Exception $e) {

            $response = $e->getResponse();
            if(!empty(json_decode((string) $response->getBody(), true))) {
                $output = json_decode((string) $response->getBody(), true);
                return Response::json($output, 406);
            }

            $output = ['error' => (string) $response->getBody()];
            return Response::json($output, 406);
        }

        $output = json_decode((string) $response->getBody(), true);
        return $output;
    }

    public function logout($token = '')
    {
        $parser = new JwtParser();
        $tokenId = $parser->parse($token)->getClaim('jti');

        // revoke main token
        $token = Token::find($tokenId);
        $token->update(['revoked' => true]);

        // revoke refresh token
        DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $tokenId)->update(['revoked' => true]);

        return ['message' => 'success'];
    }

    public function user() {
        return ApiAuth::user();
    }

    public function protectedAction()
    {
        return $this->makePartial('protected');
        return 'Protected Content.';
        return ApiAuth::user(); //'Protected Content.';
    }

    public function checkAuth() {

        $http = new GuzzleHttp\Client;
        try {
            $accessToken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjllYWJhNzVkZTBmNDUzMGM1NGVhMmUxNzgwZWRlMTRmMWU2MmZkNDIzZmI0YmIwMzM0NDQ5YzEwZmZmYTYxM2UyNmNhN2IwNzdlYzE5OTVjIn0.eyJhdWQiOiIyIiwianRpIjoiOWVhYmE3NWRlMGY0NTMwYzU0ZWEyZTE3ODBlZGUxNGYxZTYyZmQ0MjNmYjRiYjAzMzQ0NDljMTBmZmZhNjEzZTI2Y2E3YjA3N2VjMTk5NWMiLCJpYXQiOjE1MDM0MDg2MzIsIm5iZiI6MTUwMzQwODYzMiwiZXhwIjoxNTM0OTQ0NjMyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.BTBKDFABA0ttn4416xXJZN0B3SEa8TFthV5SKTj86tL9TT_4WPGyQFB-b8P48DoMKuiaXQwE-lUYZwJ_XDJmwiAPN4roHbsbVbiwZiWyXAe7jHXbZvi0bIYROT4ik2A0voYkPGwUQyBQ05Ejr6AR3-f4dYHF5pqFYsrZ0VM9irRnNu3WS5m3k3o_h7ZGjR7sKCAMfMj-kxjaG4taIXWbLyS58ykp1liX54NKlm2OA5cc1GKBJqDFqgAPHhzCYMYusb_SaTQtYtZHl8eh5zCHB2euRrqotngEC1uJGeIKwOLcoTJXKCW5SWIbzB63cKPJT5AegMLQN0FLhOuUh-FIdtDk9LjdH8tL-3vSXyFL3IF3Vo-q-85V6C9oksXtRK3eLvcoJ0T-x7ACuaZxuKPYo2lpSlkUamS94DZaXlM5hl3OkfXGcc16qFD6Y3WxqWdXJfLxJyY4DQROIQyVapfiKo6iHSnXgr7tcUMajGJLEmEk_k4XesSSNzBYdkncZ_8USiNmuyPQVv5-8454IYsQMkjPDax2oMl7ZCLA7nfq2br3wBpj49rXbjI-WNY9v-KdIQg0r051EZhLROhf1vyBTawr04pCWFBhvc2IqpvOA3h4jq1kvvzMB62ISpm9gKXtdFMwrjsyHtP4JdsofoLfsQa-AN520MXIEj4GMaX9cTk";
            $response = $http->post('http://7.localhost/vuejs/my_frame_work_ready/my-framework-dist/v1/api/login/protectedaction', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$accessToken,
                ],
                'form_params' => [],
            ]);
        } catch (\Exception $e) {
            $response = $e->getResponse();
            if(!empty(json_decode((string) $response->getBody(), true))) {
                return json_decode((string) $response->getBody(), true);
            }
            // return ['error' => (string) $response->getBody()];
            return (string) $response->getBody();
        }

        $output = json_decode((string) $response->getBody(), true);
        return $output;
    }

}