<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class DashboardController extends Controller
{
    public function login(){
        return view('login');
    }
    public function signin(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {

            return redirect()->route('home')->with(['success' => 'You have logged in']);

        }
        return redirect()->back()->with(['info' => 'Invalid Information']);
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('home')->with(['success' => 'You have logged out']);
    }
}
