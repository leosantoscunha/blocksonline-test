<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{

    /**
     * @var LoginService
     */
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @param LoginService $loginService
     * @return null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        try {
            if ($this->loginService->attemptLoginAPI($request)) {
                return response()->json(['status' => true, 'message' => 'User logged successfully!']);
            }
        } catch (\Throwable $t) {
            logger()->error($t);
            $message = $t->getMessage();
        }
        return response()->json(['status' => false, 'message' => $message], 403);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
                'username' => 'required|string',
                'password' => 'required|string'
            ]
        );
    }

    public function logout()
    {
        $this->loginService->secSessionStart();
        Session::flush();

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        session_destroy();

        return redirect()->route('login');
    }

}
