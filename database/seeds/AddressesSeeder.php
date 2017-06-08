<?php

use App\Address;
use Illuminate\Database\Seeder;

class AddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'line_1' => "3250 Sweetwater Rd.",
            'line_2' => "Apt. 406",
            'postal_code' => '30044',
            'location_id' => 1,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "5468 Terrace Garden Way",
            'postal_code' => '30071',
            'location_id' => 2,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "921 Lawton St SW",
            'postal_code' => '30310',
            'location_id' => 3,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "314 Anders Pth NW",
            'postal_code' => '30064',
            'location_id' => 4,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "3640 Clairmont Rd",
            'postal_code' => '30341',
            'location_id' => 5,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "3049 Mill Run Ct",
            'postal_code' => '30097',
            'location_id' => 6,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "206 Kings Ct",
            'postal_code' => '30269',
            'location_id' => 7,
            'country_id' => 1
        ]);

        Address::create([
            'line_1' => "770 Brand South Trail",
            'postal_code' => '30046',
            'location_id' => 8,
            'country_id' => 1
        ]);

    }
}
