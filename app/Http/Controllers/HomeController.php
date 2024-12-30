<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
use App\Http\Helper;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');  // Ensure the user is authenticated
    }

    //// Dashboard
    public function dashboard()
    {
        $data = array();
        if (Session::has('loginId')) {
            $user = User::where('id', '=', Session::get('loginId'))->first();
            //the token will be stored into table personal_access_tokens wiht hashed token.
            // Create a token for SPBPSM
            $token = $user->createToken('SPBPSM')->plainTextToken;
            //dd($user);
            return view('dashboard', compact('user'));
        }
        return redirect('/');
    }

    public function redirect_to($to)
    {
        // Get the authenticated user
        $user = Auth::user();
        // Create a token for STK
        $token = $user->createToken('STK')->plainTextToken;
        if ($to == 'STK') {
            // return redirect('http://stk.local/authenticate?token=' . $token);
            return redirect('http://10.22.28.183/stk/authenticate?token=' . $token);
          
        } else {
            return redirect('dashboard');
        }
    }
}
