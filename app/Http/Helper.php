<?php

namespace App\Http;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\password_reset_tokens;


class Helper
{

    public static function getUnitsId($id) //id unit in table users
    {
        return Unit::where('id', $id)->first();
    }
    public static function getDepartmentsId($id) //id department in table users
    {
        return Department::where('id', $id)->first();
    }

    public static function getUnitsByDepartment($departmentId)
    {
        return Unit::where('department_id', $departmentId)->get();
    }

    public static function gettokenpinas()
    {
        return PersonalAccessToken::where('tokenable_id', Auth::user()->id)
            ->orderBy('id', 'desc') // Apply orderBy before get()
            ->first()->token;
    }

    public static function user_details($userid)
    {
        $user = User::where('id', $userid)->first();
        //dd($user->Fullname);
        return $user;
    }

    public static function email_forgot_pass($id,$token)
    {
            //dd($id);

            // Retrieve the email from the request
            $data = Helper::user_details($id);
            
            //dd($data);
            $email = $data->Email;
            $id = $data->id;

            $subject = "[BPSM] Tetapan Semula Kata Laluan"; 
            $content = [
                'data' => $data,
                'token' => $token,
                'id' => $id,
            ];

            Mail::to($email)->send(new ForgotPasswordEmail($subject, $content));
    
    }
}
