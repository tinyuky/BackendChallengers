<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use JWTAuth;
use App\User;

class AuthController extends Controller
{  
    //Confi middleware for controller 
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['login']]);
    } 
    //Login fuction
    //Check remember token -> check account -> response JSON
    public function login(Request $request)
    {
        //Check remember token
        //True
        if(auth()->user()){
            //Check account
            $user = auth()->user();
            //Active
            if($user['status']=='active'){
                //refresh a new token and response JSON
                $newtoken = auth()->refresh();
                return $this->respondWithToken($newtoken);
            }
            //Deactive
            else{           
                return response()->json(['error' => 'Sorry token is deactive'], 401);
            }
        }
        //False
        else{
            //Check account by attempt (email, password, status)
            $credentials = request(['email', 'password']);
            $credentials['status']= 'active';
            //Deactive
            if (! $token = auth()->setTTL(21600)->attempt($credentials)) {
                //response
                return response()->json(['error' => "Sorry account is not correct or deactive"], 401);
            }
            //Active
            //response
            return $this->respondWithToken($token);
        }               
    }

    //Construct of JSON
    protected function respondWithToken($token)
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL().' minutes'
        ]);
    }

    //Logout function
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'],200);
    }
}
