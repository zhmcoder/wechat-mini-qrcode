<?php

namespace App\Api\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $successStatus  =   200;

    //----------------- [ Register user ] -------------------
    public function registerUser(Request $request)
    {

        $validator  =   Validator::make(
            $request->all(),
            [
                $this->username()             =>      'required|min:3',
                'email'             =>      'required|email',
                'password'          =>      'required|alpha_num|min:6',
                'confirm_password'  =>      'required|same:password'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $input              =       array(
            'name'          =>          $request->name,
            'email'         =>          $request->email,
            'password'      =>          bcrypt($request->password),
        );

        // check if email already registered
        $user                   =       User::where('email', $request->email)->first();
        if (!is_null($user)) {
            $data['message']     =      "Sorry! this email is already registered";
            return response()->json(['success' => false, 'status' => 'failed', 'errors' => $data]);
        }

        // create and return data
        $user                   =       User::create($input);
        $success['message']     =       "You have registered successfully";
        $token = $user->createToken('token')->accessToken;

        return response()->json(['success' => true, 'user' => $user, 'token' => $token]);
    }
    // login    //
    public function userLogin(Request $request) {
        if(Auth::attempt([$this->username()  => $request->username, 'password' => $request->password])) {

            // getting auth user after auth login
            $user = Auth::user();

            $token                  =       $user->createToken('token')->accessToken;
            $success['success']     =       true;
            $success['user']        =       $user;
            $success['token']       =       $token;
            $success['message']     =       "Success! you are logged in successfully";

            return response()->json( $success, $this->successStatus);
        }

        else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    // profile

    public function profile () {
        $user = Auth::user();
        $success['success']     =       true;
        $success['user']        =       $user;
        return response()->json($success, $this->successStatus);
    }


    // logout

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $success['success']     =       true;
        return response()->json($success, $this->successStatus);
    }

    public function username()
    {
        return 'username';
    }


}
