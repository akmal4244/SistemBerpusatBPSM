<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserPinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Helper;
use Illuminate\Support\Facades\Password;


class AuthenticationController extends Controller
{

    ////Login
    public function login()
    {
        return view('auth.login');
    }
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email:users',
            'password' => 'required|min:8|max:12'
        ]);

        //check user is exist in user_pinas table ?
        $userPinas = UserPinas::where('Email', '=', $request->email)->first();

        //if not exist
        if ($userPinas == null) {
            $user = User::where('email', '=', $request->email)->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $request->session()->put('loginId', $user->id);
                    Auth::login($user); // Logs in the user
                    $request->session()->regenerate(); // Regenerate session ID     
                    return redirect()->route('dashboard')->with('success', 'Berjaya Log Masuk!');
                } else {
                    return back()->with('fail', 'Emel atau Kata Laluan anda tidak sah!');
                }
            } else {
                return redirect('/register')->with('fail', 'Akaun Anda Tiada Dalam Rekod. Mohon Daftar Akaun Baru');
            }
        } else { // exist in users_pinas table
            //dd('exist in users_pinas table', $userPinas);

            $depart = Department::where('title', 'like', '%' . $userPinas->Department . '%')->first();
            if ($depart) {
                $depart = $depart->id;
            } else {
                $depart = 0;
            }

            $unit = Unit::where('title', 'like', '%' . $userPinas->Unit . '%')->first();
            if ($depart) {
                $unit = $unit->id;
            } else {
                $unit = 0;
            }
            //dd($userPinas->Password);
            //check if the user is first time login or not. if 0, means the user not a frist time login.
            if ($userPinas->needs_password_reset == 0) { //if not a first time login

                $UserPinasPassword = Hash::make($userPinas->Password);
                $requestPassword = $request->password;

                //check the password in user_pinas is same as request password?
                if (Hash::check($requestPassword, $UserPinasPassword)) {
                    // Passwords match
                    //dd('match');
                    //save into db
                    //copy data from user_pinas table to user table
                    //password will be hased
                    $user = new User();
                    $user->name = $userPinas->Fullname;
                    $user->email = $userPinas->Email;
                    $user->password = $UserPinasPassword;
                    $user->role = $userPinas->Role;
                    $user->ic = $userPinas->Employee_ID;
                    $user->profile_picture = $userPinas->Profile_Picture;
                    $user->date_created = $userPinas->Date_Created;
                    $user->position = $userPinas->Position;
                    $user->department = $depart;
                    $user->unit = $unit;
                    $user->phone = $userPinas->Telephone;
                    $result = $user->save();

                    $request->session()->put('loginId', $user->id);
                    Auth::login($user); // Logs in the user
                    $request->session()->regenerate(); // Regenerate session ID     
                    return redirect()->route('dashboard')->with('success', 'Berjaya Log Masuk!');
                } else {
                    // Passwords do not match
                    //dd('not match');
                    return back()->with('fail', 'Emel atau Kata Laluan anda tidak sah!');
                }
            } else { //if user first time login

                //save into db
                //copy data from user_pinas table to user table
                // $user = new User();
                // $user->name = $userPinas->Fullname;
                // $user->email = $userPinas->Email;
                // $user->password = $userPinas->Password; //password will not saved
                // $user->role = $userPinas->Role;
                // $user->ic = $userPinas->Employee_ID;
                // $user->profile_picture = $userPinas->Profile_Picture;
                // $user->date_created = $userPinas->Date_Created;
                // $user->position = $userPinas->Position;
                // $user->department = $depart;
                // $user->unit = $unit;
                // $user->phone = $userPinas->Telephone;

                // $result = $user->save();


                // Get the user's email
                $email = $userPinas->Email;  // Example email

                // Generate a password reset token
                $token = Password::broker()->createToken(User::where('email', $email)->first());

                //return to reset password page
                return redirect()->route('password.reset', $token);
            }
        }
    }

    //Reset Password
    public function password_reset($token)
    {
        // Display the reset form
        return view('auth.reset-password', ['token' => $token]);
    }


    public function password_store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'token' => 'required',
        ]);

        $resetStatus = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($resetStatus == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Kata Laluan Telah Berjaya Diset Semula.');
        } else {
            return back()->withErrors(['email' => 'Tidak Berjaya Set Semula Kata Laluan']);
        }
    }

    //Registration
    public function register_form()
    {
        $department = Department::all();
        $unit = Unit::all();

        return view('auth.register', compact('department', 'unit'));
    }
    public function register_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:users',
            'password' => 'required|min:8|max:12'
        ]);

        //check user is exist in user_pinas table ?
        $userPinas = UserPinas::where('Email', '=', $request->email)->first();

        //if not exist
        if ($userPinas == null) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;

            $user->ic = $request->ic;
            $user->position = $request->position;
            $user->department = $request->department;
            $user->unit = $request->unit;
            $user->phone = $request->phone;

            $result = $user->save();
        } else { //exist in users_pinas table
            dd('exist in user_pinas table');
        }

        if ($result) {
            return back()->with('success', 'You have registered successfully.');
            return redirect('/')->with('success', 'Akaun Anda Telah Berjaya Direkod');
        } else {
            return back()->with('fail', 'Something wrong!');
        }
    }


    ///Logout
    public function logout()
    {
        // Revoke all tokens for the authenticated user
        Auth::user()->tokens()->delete();

        Auth::logout(); // Log the user out
        session()->flush();  // Clear the session
        return redirect('/')->with('success', 'You have been logged out!');
    }
}
