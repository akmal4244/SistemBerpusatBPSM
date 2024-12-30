<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Helper;
use Illuminate\Support\Facades\Password;
use App\Models\password_reset_tokens;



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
            // 'email' => 'required|email:users',
            'Employee_ID' => 'required|numeric',
            'password' => 'required|min:8|max:12'
        ], [
            'Employee_ID.required' => 'Mohon masukkan nombor kad pengenalan anda',
            'password.required' => 'Mohon masukkan kata laluan anda',
        ]);
        //check user is exist in user_pinas table ?
        // $user = User::where('Email', '=', $request->email)->first();
        $user = User::where('Employee_ID', '=', $request->Employee_ID)->first();

        //if not exist
        if ($user == null) {
            return redirect('/register')->with('error', 'Akaun Anda Tiada Dalam Rekod. Mohon Daftar Akaun Baru');
        } else {
            //if exist
            //check status user
            if ($user->Status_Aktif == '0') { 
                return back()->with('error', 'Akaun anda tidak aktif. Sila hubungi Unit Teknikal dan ICT untuk sebarang pertanyaan.');
            //check if need to reset password ?
            }elseif ($user->needs_password_reset == '0') { //dd('2');
                //if no, go login page
                if (Hash::check($request->password, $user->Password)) {
                    $request->session()->put('loginId', $user->id);
                    Auth::login($user); // Logs in the user
                    $request->session()->regenerate(); // Regenerate session ID     
                    return redirect()->route('dashboard')->with('success', 'Berjaya Log Masuk!');
                } else {
                    return back()->with('error', 'Emel atau Kata Laluan anda tidak sah!');
                }
            } else {
                //if yes, go reset page
                // Get the user's email
                //$email = $user->Email;  // Example email
                $Employee_ID = $user->Employee_ID;

                $user = User::where('Employee_ID', $Employee_ID)->first();

                if (!$user) {
                    return response()->json(['message' => 'User not found.'], 404);
                } else {
                    $token = Password::broker()->createToken($user);


                    //$token = Password::broker()->createToken(User::where('Employee_ID', $Employee_ID)->first());

                    //return to reset password page
                    return redirect()->route('password.reset', [$token, $user->id])->with('success', 'Sila Kemaskini Profil');
                }
            }
        }
    }




    //Reset Password
    public function password_reset($token, $id)
    {

        $user = User::where('id', $id)->first();
        $user_token = password_reset_tokens::where('email', $user->Email)->first();

        if ($user && Hash::check($token, $user_token->token)) {
            // Token is valid
            // Display the reset form
            return view('auth.reset-password', ['token' => $token, 'user' => $user]);
        } else {
            return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired token.']);
        }
    }

    public function password_store(Request $request)
    {
        // Custom validation messages
        $customMessages = [
            'Employee_ID.required' => 'Sila masukkan no. kad pengenalan',
            'Employee_ID.exists' => 'No. kad pengenalan tiada dalam rekod',
            'Position' => 'Sila masukkan Jawatan, Skim & Gred',
            'Telephone' => 'Sila masukkan No. Telefon',
            'Telephone.numeric' => 'No. Telefon mesti mengandungi nombor sahaja.',
            'password.confirmed' => 'Pengesahan kata laluan tidak sama',
            'password.required' => 'Sila masukkan kata laluan',
            'password.min' => 'Kata Laluan mestilah sekurang-kurangnya :min aksara.',
        ];

        // Validate the request
        $request->validate([
            'token' => 'required',
            'Employee_ID' => 'required|exists:users,Employee_ID',
            'Position' => 'required',
            'Telephone' => 'required|numeric',
            'password' => 'required|confirmed|min:8',
        ], $customMessages);


        // Reset the password
        $status = Password::broker()->reset(
            $request->only('Employee_ID', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                // Update user fields
                $user->Position = $request->Position;
                $user->Telephone = $request->Telephone;
                $user->password = Hash::make($password);
                $user->needs_password_reset = '0';
                $user->save();
            }
        );


        // Custom error message
        if ($status === Password::PASSWORD_RESET) {
            // Redirect to the login page with success message
            return redirect()->route('login.form')->with('success', __('Kata laluan telah berjaya disimpan'));
        } else {
            // Custom error message for failure
            return back()->with('error', __('Maaf..Set semula kata laluan tidak berjaya disimpan'));
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
            //'email' => 'required|email:users',
            'Employee_ID' => 'required|numeric',
            'password' => 'required|min:8|max:12'
        ]);

        //check user is exist in user_pinas table ?
        $user = User::where('Employee_ID', '=', $request->Employee_ID)->first();

        //if not exist
        if ($user == null) {

            $user = new User();
            $user->Fullname = $request->name;
            $user->Employee_ID = $request->Employee_ID;
            $user->Password = Hash::make($request->password);
            $user->Employee_ID = $request->ic;
            $user->Position = $request->position;
            $user->Department = $request->department;
            $user->Unit = $request->unit;
            $user->Telephone = $request->phone;
            $result = $user->save();
            // Update the user_id column with the newly created ID
            $user->User_ID = $user->id; // Use the $user->id property directly
            $user->save();
        } else { //exist in users_pinas table
            return back()->withErrors(['Employee_ID' => 'Tidak Berjaya. Akaun Tersebut Telah Didaftar']);
        }

        if ($result) {
            return back()->with('success', 'You have registered successfully.');
            return redirect('/')->with('success', 'Akaun Anda Telah Berjaya Direkod');
        } else {
            return back()->with('error', 'Mohon Hubungi Pihak ICT.');
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
