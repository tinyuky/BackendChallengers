<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class AdminController extends Controller
{
    //Get all accounts function
    public function getall(Request $request)
    {
        return User::all();
    }
    //Get account from id function
    public function get($id)
    {
        return User::find($id);
    }
    //Add account function
    public function add(Request $request)
    {
        //Validate data
        $messages = [
            'email.unique' => 'Email đã sử dụng',
            'staffid.unique' => 'Mã nhân viên đã sử dụng',
        ];
        $validator = Validator::make($request->all(), [
            'staffid' => 'required|unique:users,staffid|',
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'otheremail' => 'required|email',
            'password' => 'required',
            'phone1' => 'required',
            'phone2' => 'required',
            'role' => 'required',
        ], $messages)->validate();

        //Add account to database
        $user = new User;
        $user->staffid = $request->input('staffid');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->otheremail = $request->input('otheremail');
        $user->password = bcrypt($request->input('password'));
        $user->phone1 = $request->input('phone1');
        $user->phone2 = $request->input('phone2');
        $user->status = true;
        $user->role = $request->input('role');
        $user->save();
        return response()->json(['message' => 'Add account success']);
    }
    //Edit account function
    public function update(Request $request)
    {
        $user = User::find($request->input('id'));

        $messages = [
            'email.unique' => 'Email đã sử dụng',
            'staffid.unique' => 'Mã nhân viên đã sử dụng',
        ];
        $validator = Validator::make($request->all(), [
            'staffid' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id),
            ],
            'name' => 'required',
            'otheremail' => 'required|email',
            'password' => 'required',
            'phone1' => 'required',
            'phone2' => 'required',
            'role' => 'required',
            'status' => 'required',
        ], $messages)->validate();

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
        return response()->json(['message' => 'Update account success']);
    }
}
