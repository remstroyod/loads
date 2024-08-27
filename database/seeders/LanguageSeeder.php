<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('languages')->insert([
            [
                'name' => 'Dansk',
                'code' => 'DK',
            ],
            [
                'name' => 'Deutsch',
                'code' => 'DE',
            ],
            [
                'name' => 'Eesti',
                'code' => 'EE',
            ],
            [
                'name' => 'English',
                'code' => 'GB',
            ],
            [
                'name' => 'Español',
                'code' => 'ES',
            ],
            [
                'name' => 'Français',
                'code' => 'FR',
            ],
            [
                'name' => 'Hrvatski',
                'code' => 'HR',
            ],
            [
                'name' => 'Italiano',
                'code' => 'IT',
            ],
            [
                'name' => 'Latviešu',
                'code' => 'LV',
            ],
            [
                'name' => 'Lietuvių',
                'code' => 'LT',
            ],
            [
                'name' => 'Magyar',
                'code' => 'HU',
            ],
            [
                'name' => 'Nederlands',
                'code' => 'NL',
            ],
            [
                'name' => 'Norsk',
                'code' => 'NO',
            ],
            [
                'name' => 'Polska',
                'code' => 'PL',
            ],
            [
                'name' => 'Português',
                'code' => 'PT',
            ],
            [
                'name' => 'Română',
                'code' => 'RO',
            ],
            [
                'name' => 'Slovenski',
                'code' => 'SL',
            ],
            [
                'name' => 'Slovensko',
                'code' => 'SK',
            ],
            [
                'name' => 'Srpski',
                'code' => 'RS',
            ],
            [
                'name' => 'Suomi',
                'code' => 'FI',
            ],
            [
                'name' => 'Svenska',
                'code' => 'SE',
            ],
            [
                'name' => 'Türkçe',
                'code' => 'TR',
            ],
            [
                'name' => 'Česky',
                'code' => 'CZ',
            ],
            [
                'name' => 'Ελληνικά',
                'code' => 'GR',
            ],
            [
                'name' => 'Български',
                'code' => 'BG',
            ],
            [
                'name' => 'Русский',
                'code' => 'RU',
            ],
            [
                'name' => 'Українська',
                'code' => 'UA',
            ],
        ]);

    }
}
