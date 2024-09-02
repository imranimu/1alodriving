<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && Auth::user()->is_role == 1) {
                return redirect('admin/dashboard');
            } elseif (Auth::guard($guard)->check() && Auth::user()->is_role == 2) {
                $current_time = Carbon::now()->timestamp;
                $question_expires_at = strtotime(Auth::user()->question_expires_at);
                if (Auth::user()->is_question_verify == '1' && Auth::user()->question_expires_at != "" && $question_expires_at > $current_time) {
                    // return redirect('student/dashboard');
                    return redirect('student/course-lists');
                } else {
                    return redirect('student/login-security-question');
                }
            }
            
            /*elseif (Auth::guard($guard)->check() && Auth::user()->is_role == 2) {
                return redirect('student/dashboard');
            }*/
        }

        return $next($request);
    }
}
