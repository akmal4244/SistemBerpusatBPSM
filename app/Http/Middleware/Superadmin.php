<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserMgtController;

class Superadmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('id', Auth::user()->id)->first();

        if($user->Role == 'Superadmin'){
            return $next($request);
        }else{
            return redirect('login')->with('fail','Anda tiada akses untuk ke halaman ini.');
        }
        
    }
}
