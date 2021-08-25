<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{

    public function __construct(Request $request, LoginService $loginService)
    {
        $loginService->secSessionStart();
        if(!$loginService->loginCheck($request)) {
            redirect('/login');
        }
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);
        if (empty($user)) {
            return redirect('login');
        }

        return view('dashboard', compact('user'));
    }
}
