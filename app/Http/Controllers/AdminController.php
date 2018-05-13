<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{
    public function getall(Request $request){
        try{
            return User::all();
        }
        catch(Exception $e){
            return response()->json(['error'=>'Connect database fail'],400);
        }
    }
    public function get($id){   
        try{
            return User::find($id);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Connect database fail'],400);
        }
    }
    public function add(Request $request){
        try{
            $user = new User;
            $user->staffid = $request->input('staffid');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->otheremail = $request->input('otheremail');
            $user->password = bcrypt($request->input('password'));
            $user->phone1 = $request->input('phone1');
            $user->phone2 = $request->input('phone2');
            $user->status = 'active';
            $user->role = $request->input('role');
            $user->save();
            return response()->json(['message'=>'Add account success']);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Add account fail'],400);
        }
    }
    public function update(Request $request){
        try{
            $user = User::find($request->input('id'));
            $user->staffid = $request->input('staffid');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->otheremail = $request->input('otheremail');
            $user->password = bcrypt($request->input('password'));
            $user->phone1 = $request->input('phone1');
            $user->phone2 = $request->input('phone2');
            $user->status = $request->input('status');
            $user->role = $request->input('role');
            $user->save();
            return response()->json(['message'=>'Update account success']);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Update account fail'],400);
        }
    }
}
