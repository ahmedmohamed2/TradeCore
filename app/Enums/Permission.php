<?php

namespace App\Enums;

enum Permission: string
{
    case ViewSystemSettings = 'system-settings.view';
    case UpdateSystemSettings = 'system-settings.update';
    case ViewUsers = 'users.view';
    case CreateUsers = 'users.create';
    case UpdateUsers = 'users.update';
    case DeleteUsers = 'users.delete';
    case ViewRoles = 'roles.view';
    case CreateRoles = 'roles.create';
    case UpdateRoles = 'roles.update';
    case DeleteRoles = 'roles.delete';

    public function group(): string
    {
        return str($this->value)->before('.')->toString();
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return list<string>
     */
    public static function userPermissions(): array
    {
        return [
            self::ViewUsers->value,
            self::CreateUsers->value,
            self::UpdateUsers->value,
            self::DeleteUsers->value,
        ];
    }

    /**
     * @return list<string>
     */
    public static function rolePermissions(): array
    {
        return [
            self::ViewRoles->value,
            self::CreateRoles->value,
            self::UpdateRoles->value,
            self::DeleteRoles->value,
        ];
    }
}
