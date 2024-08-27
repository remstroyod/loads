<?php
namespace App\Enums;

enum UserDriverDocumentTypeEnum: int
{

    case Passport = 1;
    case Card = 2;
    case License = 3;

    public function description(): string
    {
        return match($this)
        {
            self::Passport       => __('Passport'),
            self::Card           => __('Identity Card'),
            self::License        => __('Driving License '),
        };
    }

}
