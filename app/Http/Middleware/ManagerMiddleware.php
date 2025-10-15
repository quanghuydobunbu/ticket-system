<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Kiểm tra có role không
        if (!$user->role || !$user->role->role) {
            abort(403, 'Unauthorized');
        }
        
        $roleName = $user->role->role->name;
        
        // Kiểm tra role
        if (in_array($roleName, ['admin', 'organizer'])) {
            return $next($request);
        }
        
        abort(403, 'Bạn không có quyền truy cập');
    }
}
