<?php
namespace App\Enums;

enum UserStatusEnum: int
{

    case NotActive = 0;
    case Active = 1;

    public function description($locale = 'en'): string
    {
        return match($this)
        {
            self::NotActive  => __(key: 'users.status.notactive', locale: $locale),
            self::Active  => __(key: 'users.status.active', locale: $locale),
        };
    }

}
