<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }
    public function loginAuth(Request $request){
        $validator = Validator::make($request->all(),[
            "email" => "required|email:dns|",
            "password" => "required|string"
        ]);

        if($validator->fails())
        {
            return redirect()->route('login')->with('failed',$validator->errors());
        }

        $validated = $validator->validated();

        $user = $request->only(['email','password']);
        
        if(Auth::attempt($user))
        {
            return redirect()->route('dashboard')->with('success','Anda Berhasil Login');
        }else{
            return redirect()->back()->with('failed','Login Gagal,Silahkan Coba Lagi !');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success','Anda Berhasil Logout');
    }
}
