<?php

use App\Location;
use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            'longitude' => -84.11286189999998,
            'latitude' => 33.94949140000001
        ]);

        Location::create([
            'longitude' => -84.19312939999998,
            'latitude' => 33.9308782
        ]);

        Location::create([
            'longitude' => -84.423698,
            'latitude' => 33.72989099999999
        ]);

        Location::create([
            'longitude' => -84.57756042480469,
            'latitude' => 33.959308210392024
        ]);

        Location::create([
            'longitude' => -84.30981650000001,
            'latitude' => 33.8659641
        ]);

        Location::create([
            'longitude' => -84.15726619999998,
            'latitude' => 34.0257051
        ]);

        Location::create([
            'longitude' => -84.60525899999999,
            'latitude' => 33.398388
        ]);

        Location::create([
            'longitude' => -83.97016940000003,
            'latitude' => 33.9351115
        ]);

    }
}
