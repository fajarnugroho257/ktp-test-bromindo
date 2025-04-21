<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\baseController\AppController;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    // public function __construct(AppController $AppController)
    // {
    //     $this->container = $AppController;
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('login');
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        // dd($request->all());
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            logActivity('Login', 'User berhasil login');
            // return redirect()->intended('dashboard');
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'Data tidak ditemukan']);
    }

    public function logOut(Request $request)
    {
        logActivity('Logout', 'User berhasil logout');
        Auth::logout();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
