<?php

namespace App\Http;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use Laravel\Sanctum\PersonalAccessToken;

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
}
