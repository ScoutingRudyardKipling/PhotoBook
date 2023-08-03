<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;

class Clearance
{
    /**
     * Checks if the logged in user has one of the permissions
     * Returns True if one of the given permissions is met
     *
     * @param array $permissions
     *
     * @return boolean
     */
    public function hasAnyPermission(array $permissions): bool
    {
        try {
            return Auth::user()->hasAnyPermission($permissions);
        } catch (\Exception $e) {
            $this->throwError(403, 'Permission does not exist');
        }
        return false;
    }

    /**
     * Checks if the logged in user has all of the permissions
     * Aborts if non of the permissions does not meet the array
     *
     * @param array $permissions
     *
     * @return void
     */
    public function hasAnyPermissionOrAbort(array $permissions)
    {
        if ($this->hasAnyPermission($permissions) === false) {
            $this->throwError(403, 'action not allowed for user ' . Auth::user()->name);
        }
    }

    /**
     * Checks if the logged in user has all of the permissions
     * Returns True if all of the given permissions is met
     *
     * @param array $permissions
     *
     * @return boolean
     */
    public function hasAllPermissions(array $permissions): bool
    {
        try {
            return Auth::user()->hasAllPermissions($permissions);
        } catch (Exception $e) {
            $this->throwError(403, 'Permission does not exist');
        }
        return false;
    }

    /**
     * Checks if all permissions of list one is set, or all permissions of list two are set.
     * If one of the two lists passes, the function will pass.
     *
     * @param array $permissionsListOne
     * @param array $permissionsListTwo
     *
     * @return boolean
     */
    public function hasAllOrAllPermissions(array $permissionsListOne, array $permissionsListTwo): bool
    {
        if ($this->hasAllPermissions($permissionsListOne) === true || $this->hasAllPermissions($permissionsListTwo) === true) {
            return true;
        }
        return false;
    }

    /**
     * Checks if all permissions of list one is set, or all permissions of list two are set.
     * If neither of the lists pass, the function will Abort.
     *
     * @param array $permissionsListOne
     * @param array $permissionsListTwo
     *
     * @return void
     */
    public function hasAllOrAllPermissionsOrAbort(array $permissionsListOne, array $permissionsListTwo)
    {
        if ($this->hasAllOrAllPermissions($permissionsListOne, $permissionsListTwo) === false) {
            $this->throwError(403, 'action not allowed for user ' . Auth::user()->name);
        }
    }

    /**
     * Checks if the logged in user has all of the permissions
     * Aborts if one or more permissions does not meet the array
     *
     * @param array $permissions
     *
     * @return void
     */
    public function hasAllPermissionsOrAbort(array $permissions)
    {
        if ($this->hasAllPermissions($permissions) === false) {
            $this->throwError(403, 'action not allowed for user ' . Auth::user()->name);
        }
    }

    /**
     * @param int    $code
     * @param string $message
     *
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function throwError(int $code, string $message)
    {
        report(new Exception($code . ': ' . $message));
        abort($code);
    }
}
