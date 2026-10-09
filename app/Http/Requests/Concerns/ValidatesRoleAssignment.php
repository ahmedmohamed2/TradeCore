<?php

namespace App\Http\Requests\Concerns;

use App\Models\User;
use App\Support\RoleName;
use Illuminate\Validation\Validator;

trait ValidatesRoleAssignment
{
    protected function guardRoleAssignment(Validator $validator, ?User $subject = null): void
    {
        $roles = $this->input('roles', []);

        if (! is_array($roles)) {
            return;
        }

        $actor = $this->user();

        if (in_array(RoleName::SuperAdmin, $roles, true) && ! $actor?->hasRole(RoleName::SuperAdmin)) {
            $validator->errors()->add('roles', __('users.cannot_assign_super_admin'));
        }

        if (! $subject instanceof User || ! $subject->hasRole(RoleName::SuperAdmin)) {
            return;
        }

        if (in_array(RoleName::SuperAdmin, $roles, true)) {
            return;
        }

        if (User::role(RoleName::SuperAdmin)->count() <= 1) {
            $validator->errors()->add('roles', __('users.cannot_remove_last_super_admin'));
        }
    }
}
