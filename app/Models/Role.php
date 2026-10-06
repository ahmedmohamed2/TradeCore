<?php

namespace App\Models;

use App\Support\RoleName;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Session login uses Sanctum, which has no user provider. Roles stay on the web guard
     * so user counts and assignments resolve to the User model.
     */
    protected string $guard_name = RoleName::Guard;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->name === RoleName::SuperAdmin;
    }
}
