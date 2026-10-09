<?php

namespace App\Support;

use App\Enums\Permission as PermissionName;
use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionCatalog
{
    public function __construct(private PermissionRegistrar $registrar) {}

    public function sync(): void
    {
        $this->registrar->forgetCachedPermissions();

        foreach (PermissionName::cases() as $permission) {
            Permission::findOrCreate($permission->value, RoleName::Guard);
        }

        Role::findOrCreate(RoleName::SuperAdmin, RoleName::Guard);

        $this->registrar->forgetCachedPermissions();
    }

    /**
     * @return array<string, list<PermissionName>>
     */
    public static function grouped(): array
    {
        $groups = [];

        foreach (PermissionName::cases() as $permission) {
            $groups[$permission->group()][] = $permission;
        }

        return $groups;
    }
}
