<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\password_reset_tokens;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)    
    {  
        $tokenData = password_reset_tokens::where('token', $request->token)
                    ->first();

          if($tokenData == null){
              return redirect()->route('password.forgot')->with('error','Token Tidak Sah!');
          }
        
        return view('auth.forgot-password-reset', compact('tokenData'));
    }

    public function reset(Request $request)
    {

        // Custom validation messages
        $customMessages = [
            'password.confirmed' => 'Pengesahan kata laluan tidak sama',
            'password.required' => 'Sila masukkan kata laluan',
            'password.min' => 'Kata Laluan mestilah sekurang-kurangnya :min aksara.',
        ];

        // Validate the request
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ], $customMessages);

        $tokenData = password_reset_tokens::where('token', $request->token)
        ->first();
        

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Token Tidak Sah!']);
        }

        // Update the user's password
        $user = User::where('Email', $tokenData->Email)->first();
        if (!$user) {
            return back()->withErrors(['error' => 'Pengguna tiada dalam rekod']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')->where('Email', $tokenData->Email)->delete();

        return redirect()->route('login.form')->with('success', 'Kata Laluan Baru Telah Berjaya Disimpan');
    }
}
