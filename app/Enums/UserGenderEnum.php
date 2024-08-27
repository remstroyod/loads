<?php
namespace App\Enums;

enum UserGenderEnum: int
{

    case Male = 1;
    case Female = 2;

    public function description(): string
    {
        return match($this)
        {
            self::Male       => __('Mr.'),
            self::Female       => __('Ms.'),
        };
    }

}
