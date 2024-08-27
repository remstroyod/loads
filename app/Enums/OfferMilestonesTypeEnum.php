<?php
namespace App\Enums;

use InvalidArgumentException;

enum OfferMilestonesTypeEnum: int
{

    case None = 0;
    case Onloading = 1;
    case Offloading = 2;

    public function description(): string
    {
        return match($this)
        {
            self::Onloading  => __('ONLOADING'),
            self::Offloading  => __('OFFLOADING'),

        };
    }

    public static function getValueFromName(string $name): int
    {

        $values = [
            'ONLOADING' => self::Onloading->value,
            'OFFLOADING' => self::Offloading->value,
        ];

        return $values[$name] ?? self::None->value;
    }


}
