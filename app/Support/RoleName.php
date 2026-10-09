<?php

namespace App\Support;

final class RoleName
{
    public const Guard = 'web';

    public const SuperAdmin = 'super-admin';

    public static function label(string $name): string
    {
        $key = 'roles.reserved.'.$name;
        $translated = __($key);

        return $translated === $key ? $name : $translated;
    }
}
