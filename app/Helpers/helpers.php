<?php

if (!function_exists('can_permission')) {
    /**
     * Check if user can perform an action on a resource
     *
     * @param  string  $resource
     * @param  string  $action
     * @return bool
     */
    function can_permission(string $resource, string $action): bool
    {
        return \App\Helpers\PermissionHelper::can($resource, $action);
    }
}

if (!function_exists('can_access')) {
    /**
     * Check if user has access to a route
     *
     * @param  string  $routeName
     * @return bool
     */
    function can_access(string $routeName): bool
    {
        return \App\Helpers\PermissionHelper::canAccess($routeName);
    }
}
