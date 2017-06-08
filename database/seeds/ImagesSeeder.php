<?php

use App\Image;
use Illuminate\Database\Seeder;

class ImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // user

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);


        // services

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        Image::create([
            'path' => 'genericservice.png'
        ]);

        // Employees

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);

        Image::create([
            'path' => 'genericprofile.jpg'
        ]);
    }
}
