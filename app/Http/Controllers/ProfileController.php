<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Unit;
use App\Models\Department;
use App\Models\password_reset_tokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Ensure the user is authenticated
    }
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        $department = Department::get();
        $unit = Unit::get();
        return view('profile.edit', Compact('user', 'department', 'unit'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'Fullname' => 'required',
            'Email' => 'required|email',
            'Telephone' => 'required|numeric',
            'Position' => 'required',
            'Department' => 'required',
            'Unit' => 'required',
        ], [
            'Fullname.required' => 'Sila masukkan nama penuh',
            'Email.required' => 'Sila masukkan email',
            'Email.email' => 'Sila masukkan email yang betul',
            'Position.required' => 'Sila masukkan position',
            'Department.required' => 'Sila pilih cawangan',
            'Unit.required' => 'Sila masukkan unit',
        ]);

        // dd($request->has('remove_image') && $request->remove_image == 'on');
        $user = User::findOrFail($id);

        // Remove existing image if checkbox is checked
        if ($request->has('remove_image') && $request->remove_image == 'on') {
            if ($user->Profile_Picture && Storage::exists('public/profile/' . $user->Profile_Picture)) {
                Storage::delete('public/profile/' . $user->Profile_Picture);
            }
            User::where('id', $id)->update(['Profile_Picture' => null]);
        }

        // Handle file upload
        if ($request->hasFile('Profile_Picture')) {
            // Handle file upload
            $file = $request->file('Profile_Picture');
            $filename = Auth::user()->id . $file->getClientOriginalName();
            $file->storeAs('profile', $filename, 'public'); // Save to storage/app/public/profile
            $validated['Profile_Picture'] = $filename;
            $data = $user->update($validated);
        } else {
            $data = $user->update($validated);
        }

        if ($data) {
            return redirect()->route('profile.edit', $id)->with('success', 'Butiran Pengguna Telah Berjaya Dikemaskini.');
        } else {
            return redirect()->route('profile.edit', $id)->with('error', 'Butiran Pengguna Tidak Berjaya Dikemaskini.');
        }
    }

    public function password_reset($id)
    {
        $user = User::findOrFail($id);

        return view('profile.password_reset', Compact('user'));
    }

    public function password_reset_submit(Request $request, $id)
    {   
        $user = User::where('id', $id)->first();
    
        //dd($request->all());

          // Validate the input
          $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
            'new_password_confirmation' => 'required',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->Password)) {
            return back()->with('error', 'Kata laluan lama tidak betul.');
        }

        // Update the user's password
        $user->Password = Hash::make($request->new_password);
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Kata laluan berjaya dikemas kini.');


    }
}
