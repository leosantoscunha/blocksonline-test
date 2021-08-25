<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginService
{

    function secSessionStart()
    {
        $session_name = 'sec_session_id';
        $secure = false;
        $httponly = true;

        ini_set('session.use_only_cookies', 1);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
        session_name($session_name);
        session_start();
        session_regenerate_id(true);
    }

    function loginCheck(Request $request): bool
    {

        $userId = Session::get('user_id');

        if ($userId) {
            $login_string = Session::get('__token');

            $ip_address = $request->getClientIp();
            $user_browser = $request->userAgent();

            $password = User::find($userId);

            if (!empty($password)) {
                $login_check = $this->hashLoginCheck($password, $ip_address, $user_browser);
                return $login_check === $login_string;
            }
        }

        return false;
    }

    /**
     * @param $request
     * @return bool
     * @throws AuthenticationException
     */
    public function attemptLoginAPI(Request $request): bool
    {
        $credentials = $request->only('username', 'password');

        if (User::query()->where('user_name', $credentials['username'])->exists()) {
            $user = User::query()->where('user_name', $credentials['username'])->first();
            if (Hash::check($credentials['password'], $user->user_password)) {
                Session::start();

                $user_id = preg_replace("/[^0-9]+/", "", $user->id); // XSS Protection
                Session::put('user_id', $user_id);
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user->user_name); // XSS Protection
                Session::put('username', $username);

                $ip_address = $request->getClientIp();
                $user_browser = $request->userAgent();

                Session::put('__token', $this->hashLoginCheck($user->user_password, $ip_address, $user_browser));

                return true;
            } else {
                throw new AuthenticationException(trans('auth.failed'));
            }
        } else {
            throw new ModelNotFoundException("User not found!\n Please create a user to do login", 404);
        }

    }

    /**
     * @param $password
     * @param string|null $ip_address
     * @param string|null $user_browser
     * @return string
     */
    private function hashLoginCheck($password, ?string $ip_address, ?string $user_browser): string
    {
        return hash('sha512', $password . $ip_address . $user_browser);
    }

}
