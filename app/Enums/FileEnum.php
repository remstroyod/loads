<?php
namespace App\Enums;

use InvalidArgumentException;

enum FileEnum: int
{

    case none = 0;
    case passport = 1;
    case cmr = 2;
    case license = 3;

    public function description(): string
    {
        return match($this)
        {
            self::none  => __('none'),
            self::passport  => __('passport'),
            self::cmr       => __('cmr'),
            self::license   => __('license'),
        };
    }

    public static function getValueFromName(string $name): int
    {

        $values = [
            'passport' => self::passport->value,
            'cmr' => self::cmr->value,
            'license' => self::license->value,
        ];

        return $values[$name] ?? self::none->value;
    }


}
