<?php
namespace App\Enums;

enum UserSubcontractorsEnum: int
{

    case No = 1;
    case YesParty = 2;
    case YesExclusively = 3;

    public function description(): string
    {
        return match($this)
        {
            self::No                    => __('No'),
            self::YesParty              => __('Yes - party with subcontractors'),
            self::YesExclusively        => __('Yes - exclusively with subcontractors'),
        };
    }
}
