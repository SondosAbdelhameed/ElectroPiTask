<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(RegisterRequest $request)
    {
        try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

         return new SuccessResource(Response::HTTP_OK,"User Registered Successfully.");

        } catch (\Exception $ex) {
            return new ErrorResource(Response::HTTP_BAD_REQUEST,null,$ex->getTrace());
        } 
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return new ErrorResource(Response::HTTP_UNAUTHORIZED,'Credential not match');
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;
        $user->token_type = 'Bearer';
        return new LoginResource($user);    
    }


    public function logout()
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return new SuccessResource(Response::HTTP_OK,"User Logout Successfuly.");
        } catch (\Exception $ex) {
            return new ErrorResource(Response::HTTP_BAD_REQUEST,null,$ex->getTrace());
        }     
    }
}
