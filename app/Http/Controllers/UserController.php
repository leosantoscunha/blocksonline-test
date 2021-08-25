<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use App\Services\LoginService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    /**
     * UserController constructor.
     * @param Request $request
     * @param LoginService $loginService
     */
    public function __construct(Request $request, LoginService $loginService)
    {
        $loginService->secSessionStart();
        if(!$loginService->loginCheck($request)) {
            redirect('/login');
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = 'Create User';
        return view('user', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'userName' => 'required|string',
            'userEmail' => 'unique:App\Models\User,user_email',
            'userPassword' => 'required|max:255',
            'password_confirmation' => 'required_with:userPassword|min:8,max:255',
            'age' => 'required',
            'address' => 'required',
            'city' => 'required',
            'post_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error to create a user', 'errors' => ['validate' => $validator->errors()]], 422);
        }

        $userName = $request->get('userName');
        $userEmail = $request->get('userEmail');
        $userPassword = $request->get('userPassword');
        $passwordConfirmation = $request->get('password_confirmation');

        if ($userPassword !== $passwordConfirmation) {
            return response()->json(['status' => false, 'message' => 'Error to create a user', 'errors' => ['validate' => "Error: Please check that you've entered and confirmed your password!"]], 422);
        }

        $userAge = $request->get('age');
        $address = $request->get('address');
        $city = $request->get('city');
        $post_code = $request->get('post_code');

        DB::beginTransaction();

        try {

            $user = User::create([
                'user_name' => $userName,
                'user_email' => $userEmail,
                'user_password' => Hash::make($userPassword),
                'age' => Carbon::createFromFormat('d/m/Y', $userAge)->format('Y-m-d'),
                'registration_date' => Carbon::createFromDate(now()),
            ]);

            $post_code = trim(str_replace('-', '', $post_code));

            $userAddresses = UserAddress::create([
                'user_id' => $user->id,
                'address' => $address,
                'city' => $city,
                'post_code' => $post_code,
            ]);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'User created successfully!']);
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error($exception->getMessage());
            return response()->json(['status' => false, 'message' => 'Error to create a user'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $title = 'Edit - ' . $user->user_name;

        return view('user', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

        $validator = \Validator::make($request->all(), [
            'user_id' => 'required',
            'userName' => 'required|string',
            'userEmail' => 'exists:users,user_email',
            'age' => 'required',
            'address' => 'required',
            'city' => 'required',
            'post_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Error to create a user', 'errors' => ['validate' => $validator->errors()]], 422);
        }

        $userName = $request->get('userName');
        $userEmail = $request->get('userEmail');
        $userAge = $request->get('age');
        $address = $request->get('address');
        $city = $request->get('city');
        $postCode = $request->get('post_code');
        $addressId = $request->get('address_id');

        DB::beginTransaction();

        try {

            $user = User::find($id);

            $user->user_name = $userName;
            $user->user_email = $userEmail;
            $userPassword = $request->get('userPassword');

            if ($userPassword) {
                $user->user_password = Hash::make($userPassword);
            }

            $user->age = Carbon::createFromFormat('d/m/Y', $userAge)->format('Y-m-d');
            $user->registration_date = Carbon::createFromDate(now());
            $user->save();

            $postCode = trim(str_replace('-', '', $postCode));

            UserAddress::find($addressId)->delete();

            UserAddress::create([
                'user_id' => $user->id,
                'address' => $address,
                'city' => $city,
                'post_code' => $postCode,
            ]);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'User updated successfully!']);
        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error($exception->getMessage());
            return response()->json(['status' => false, 'message' => 'Error to create a user'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
