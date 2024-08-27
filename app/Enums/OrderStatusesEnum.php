<?php
namespace App\Enums;

use InvalidArgumentException;

enum OrderStatusesEnum: int
{

    case Ordered = 0;
    case Verifying = 1;

    case Returned = 2;
    case Paid = 3;

    case Accepted = 4;

    public function description($locale = 'en'): string
    {
        return match($this)
        {
            self::Ordered  => __(key: 'orders.status.ordered', locale: $locale),
            self::Verifying  => __(key: 'orders.status.verifying', locale: $locale),
            self::Returned  => __(key: 'orders.status.returned', locale: $locale),
            self::Accepted  => __(key: 'orders.status.accepted', locale: $locale),
            self::Paid  => __(key: 'orders.status.paid', locale: $locale),
        };
    }

}
