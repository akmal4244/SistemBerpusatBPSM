<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\Department;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserMgtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Ensure the user is authenticated
    }

    //// list of users
    public function list()
    {
        $data = User::get();


        return view('user-mgt.list', Compact('data'));
    }

    //edit
    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        $department = Department::get();
        $unit = Unit::get();

        return view('user-mgt.edit', Compact('data', 'department', 'unit'));
    }

    //add


    public function edit_submit(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'Fullname' => 'required',
            'Employee_ID' => 'required|numeric',
            'Email' => 'required|email',
            'Role' => 'required',
            'Telephone' => 'required|numeric',
            'Position' => 'required',
            'Department' => 'required',
            'Unit' => 'required',
            'Status_Aktif' => 'required',
            'needs_password_reset' => 'required',
        ], [
            'Fullname.required' => 'Sila masukkan nama penuh',
            'Employee_ID.required' => 'Sila masukkan no. kad pengenalan',
            'Email.required' => 'Sila masukkan email',
            'Email.email' => 'Sila masukkan email yang betul',
            'Role.required' => 'Sila masukkan peranan',
            'Position.required' => 'Sila masukkan position',
            'Department.required' => 'Sila pilih cawangan',
            'Unit.required' => 'Sila masukkan unit',
            'Status_Aktif.required' => 'Sila pilih status aktif',
            'needs_password_reset.required' => 'Sila pilih status set semula kata laluan',
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
            $filename = Auth::user()->id. $file->getClientOriginalName();
            $file->storeAs('profile', $filename, 'public'); // Save to storage/app/public/profile
            $validated['Profile_Picture'] = $filename;
            $data = $user->update($validated);
        } else {
            $data = $user->update($validated);
        }
        
        if ($data) {
            return redirect()->route('user.mgt.edit', $id)->with('success', 'Butiran Pengguna Telah Berjaya Dikemaskini.');
        } else {
            return redirect()->route('user.mgt.edit', $id)->with('error', 'Butiran Pengguna Tidak Berjaya Dikemaskini.');
        }
    }
}
