<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if user can perform an action on a resource
     */
    public static function can(string $resource, string $action): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Owner can do everything
        if ($user->Role === 'Owner') {
            return true;
        }

        $permissions = [
            'menu' => [],
            'mejas' => [],
            'pesanan' => [],
            'pembayaran' => [],
            'kategori' => [],
            'karyawan' => [],
            'users' => [],
        ];

        // Kasir
        if ($user->Role === 'Kasir') {
            $kasirAllowed = [
                'pesanan' => ['create', 'edit', 'delete', 'update', 'store'],
                'pembayaran' => ['create', 'edit', 'delete', 'update', 'store'],
            ];

            return isset($kasirAllowed[$resource]) && in_array($action, $kasirAllowed[$resource]);
        }

        // Manajer
        if ($user->Role === 'Manajer') {
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

    /**
     * Check if user has access to a route
     */
    public static function canAccess(string $routeName): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Owner can access everything
        if ($user->Role === 'Owner') {
            return true;
        }

        // Define routes accessible by each role
        $routeAccess = [
            'Kasir' => [
                'dashboard',
                'pesanan',
                'pembayaran',
                'stock-menu',
                'meja',
            ],
            'Manajer' => [
                'dashboard',
                'pesanan',
                'pembayaran',
                'stock-menu',
                'meja',
                'kategori',
                'karyawan',
                'pendapatan',
                'history',
            ],
        ];

        return isset($routeAccess[$user->Role]) && in_array($routeName, $routeAccess[$user->Role]);
    }
}
