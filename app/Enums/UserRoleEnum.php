<?php
namespace App\Enums;

enum UserRoleEnum: int
{

    case User = 0;
    case Admin = 1;
    case Manager = 2;

    public function description($locale = 'en'): string
    {
        return match($this)
        {
            self::User       => __(key: 'users.roles.user', locale: $locale),
            self::Admin      => __(key: 'users.roles.admin', locale: $locale),
            self::Manager    => __(key: 'users.roles.manager', locale: $locale)
        };
    }

    public static function getRoleValueByName($role): UserRoleEnum
    {
        $roles = [
            'admin' => self::Admin,
            'manager' => self::Manager,
            'user' => self::User
        ];

        return $roles[$role];
    }

}
