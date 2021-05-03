<?php

namespace App\Providers;

use App\Assets\Utils;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        foreach ([...Utils::$adminPermissions, ...Utils::$branchPermissions] as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                $role = $user->role;
                if (!$role || !$role->permissions || !is_array($role->permissions) || count($role->permissions) <= 0) {
                    return false;
                }
                return in_array($permission, $role->permissions);
            });
        }
    }
}
