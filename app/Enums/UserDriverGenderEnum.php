<?php
namespace App\Enums;

enum UserDriverGenderEnum: int
{

    case Male = 1;
    case Female = 2;
    case Diverse = 3;

    public function description(): string
    {
        return match($this)
        {
            self::Male       => __('Male'),
            self::Female       => __('Female'),
            self::Diverse      => __('Diverse'),
        };
    }

}
