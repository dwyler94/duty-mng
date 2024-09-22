<?php

namespace App\Providers;

use App\Constants\Roles;
use App\Models\Application;
use App\Models\Child;
use App\Models\Office;
use App\Models\ShiftPlan;
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

        /**
         * Defining the user Roles
         */
        Gate::define('admin-only', function (User $user) {
            return $user->role_id === Roles::ADMIN;
        });
        $userOfficeGuard = function (User $user, Office $office) {
            if ($user->role_id === Roles::ADMIN) return true;
            if ($user->role_id === Roles::USER_B || $user->role_id === Roles::USER_A) return false;
            $user_office = $user->office;
            if (!$user_office) return false;

            if ($user->role_id === Roles::REGION_MANAGER) {
                if (!$user_office->region_id) return false;
                return $office->region_id === $user_office->region_id;
            }
            return $office->id === $user_office->id;
        };

        $shiftGuard = function (User $user, Office $office, $targetUser) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_B) return false;
            if ($user->role_id === Roles::USER_A) {
                return $user->id === $targetUser->id;
            }
            if (!$userOfficeGuard($user, $office)) return false;
            // if ($office->id !== $targetUser->office_id) return false;
            return true;
        };

        $userGuard = function (User $user, User $targetUser) {
            if ($user->role_id === Roles::ADMIN || $user->id === $targetUser->id) return true;
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return false;

            if (!$user->office) return false;
            if ($user->office->id === $targetUser->office_id) return true;
            if ($user->role_id === Roles::OFFICE_MANAGER) return false;

            // region manager
            if (!$user->office->region || !$targetUser->office) return false;
            return $user->office->region->id === $targetUser->office->id;
        };
        Gate::define('get-scheduled-working-office', function (User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_A) {
                return $user->office_id === $office->id;
            }
            return $userOfficeGuard($user, $office);
        });
        /**
         * check if $user can get shift plans of the $office
         * @param User $user
         * @param Office $office
         */
        Gate::define('get-shift-office', $userOfficeGuard);

        /**
         * check $authUser can handle $targetUser's shift
         * @param User $authUser
         * @param Office $office
         * @param User $targetUser
         */
        Gate::define('create-shift', $shiftGuard);

        /**
         * check if $user can get offices
         */
        Gate::define('get-offices', function (User $user) {
            if ($user->role_id === Roles::ADMIN) return true;
            if ($user->role_id === Roles::USER_B) return false;
            $user_office = $user->office;
            if (!$user_office) return false;
            if ($user->role_id === Roles::REGION_MANAGER && !$user->office->region) return false;
            return true;
        });

        Gate::define('get-user-working-hours', function (User $user, User $targetUser) use ($userGuard) {
            if (!$targetUser->office) return false;
            if (!$userGuard($user, $targetUser)) return false;
            return true;
        });

        $applicationGuard = function (User $user, Application $application) {
            if ($user->role_id === Roles::ADMIN) return true;
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) {
                if ($application->is_approved) return false;
                return $user->id === $application->user_id;
            }

            if (!$application->user || !$application->user->office) return false;

            $user_office = $user->office;
            if (!$user_office) return false;
            if ($user->role_id === Roles::REGION_MANAGER) {
                if (!$user->office->region) return false;
                return $user->office->region->id === $application->user->office->region_id;
            }
            if ($user->role_id === Roles::OFFICE_MANAGER) {
                return $application->user->office_id === $user_office->id;
            }
        };

        Gate::define('update-application', $applicationGuard);
        Gate::define('approve-application', function (User $user, Application $application) use ($applicationGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return false;
            return $applicationGuard($user, $application);
        });
        Gate::define('get-user-summary', function (User $user, User $targetUser) use ($userGuard) {
            if ($user->id === $targetUser->id) return true;
            return $userGuard($user, $targetUser);
        });

        Gate::define('get-office-work-total', function (User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B || $user->role_id === Roles::OFFICE_MANAGER) return false;
            return $userOfficeGuard($user, $office);
        });

        Gate::define('get-office-users', function (User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return false;
            return $userOfficeGuard($user, $office);
        });

        Gate::define('get-office-work-status', function (User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return false;
            return $userOfficeGuard($user, $office);
        });
        Gate::define('update-user-work-status', function (User $user, User $targetUser) use ($userGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return false;
            if ($user->role_id === Roles::ADMIN) return true;
            if ($user->id === $targetUser->id) return false;
            return $userGuard($user, $targetUser);
        });
        Gate::define('get-office-shift-detail', $userOfficeGuard);
        Gate::define('get-office-childcare-schedule', function(User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_A || $user->role_id === Roles::USER_B) return $user->office_id === $office->id;
            return $userOfficeGuard($user, $office);
        });
        Gate::define('delete-shift', function (User $user, ShiftPlan $shift) use ($shiftGuard) {
            $targetUser = $shift->user;
            if (!$targetUser->office) return false;
            return $shiftGuard($user, $targetUser->office, $targetUser);
        });
        Gate::define('get-child-application-table', function (User $user, Office $office) use ($userOfficeGuard) {
            if ($user->role_id === Roles::USER_B || $user->role_id === Roles::USER_A) {
                return $user->office_id === $office->id;
            };
            return $userOfficeGuard($user, $office);
        });
        $this->registerChildPolicies();
    }

    public function registerChildPolicies()
    {
        Gate::define('handle-child', function ($user, Child $child) {
            if ($user instanceof User) {
                return $user->office_id === $child->office_id;
            }
            if ($user instanceof Child) {
                return $child->id === $user->id;
            }
            return false;
        });
    }
}
