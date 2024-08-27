<?php

namespace App\Traits;

trait EnumTrait
{

    public function getTypeSelect($data, $locale = 'en')
    {

        $output = collect(array_map(function($item) use ($locale) {
            return (object)[
                'id'    => $item->value,
                'name'  => $item->description($locale),
            ];
        }, $data));

        return $output;

    }

}
