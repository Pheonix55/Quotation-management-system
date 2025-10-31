<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login');
    }
    public function registerPage()
    {
        return view('auth.register');
    }
    public function login(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);
            Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
            return redirect()->route('dashboard');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }

    }
    public function register(Request $request)
    {
        try {
            // dd($request->all());
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|min:8',
            ]);

            if (
                $data['password'] == $data['password_confirmation']
            ) {

                $data['password'] = Hash::make($data['password']);
                $user = User::create($data);
                // dd($user);
                return redirect()->route('login')->with('success', 'registration completed');
            } else {
                return back()->with('error', 'password does not match');
            }

        } catch (Throwable $th) {
            dd($th->getMessage());

        }



    }
    public function forgetPassword(Request $request)
    {
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
