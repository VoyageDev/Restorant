<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     * Format: checkPermission:resource,action
     * Example: checkPermission:karyawan,edit (check if user can edit karyawan)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $resource
     * @param  string  $action
     */
    public function handle(Request $request, Closure $next, string $resource, string $action): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($this->isAllowed($user->Role, $resource, $action)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
    }

    /**
     * Check if user role has permission for resource action
     */
    private function isAllowed(string $role, string $resource, string $action): bool
    {
        $permissions = [
            // Format: 'resource' => ['action1', 'action2', ...]
            'menu' => ['create', 'edit', 'delete', 'update', 'store'],
            'mejas' => ['create', 'edit', 'delete', 'update', 'store'],
            'pesanan' => ['create', 'edit', 'delete', 'update', 'store'],
            'pembayaran' => ['create', 'edit', 'delete', 'update', 'store'],
            'kategori' => ['create', 'edit', 'delete', 'update', 'store'],
            'karyawan' => ['create', 'edit', 'delete', 'update', 'store'],
            'users' => ['create', 'edit', 'delete', 'update', 'store'],
        ];

        // Owner can do everything
        if ($role === 'Owner') {
            return true;
        }

        // Kasir can only view/create/edit/delete pesanan, pembayaran, and view stock menu & meja
        if ($role === 'Kasir') {
            $kasirAllowed = [
                'pesanan' => ['create', 'edit', 'delete', 'update', 'store'],
                'pembayaran' => ['create', 'edit', 'delete', 'update', 'store'],
            ];

            return isset($kasirAllowed[$resource]) && in_array($action, $kasirAllowed[$resource]);
        }

        // Manajer can do everything except karyawan and users CRUD
        if ($role === 'Manajer') {
            // Manajer tidak bisa create/edit/delete karyawan
            if ($resource === 'karyawan' && in_array($action, ['create', 'edit', 'delete', 'update', 'store'])) {
                return false;
            }

            // Manajer tidak bisa create/edit/delete users
            if ($resource === 'users' && in_array($action, ['create', 'edit', 'delete', 'update', 'store'])) {
                return false;
            }

            return true;
        }

        return false;
    }
}
