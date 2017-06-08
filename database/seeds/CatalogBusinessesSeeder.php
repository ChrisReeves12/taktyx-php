<?php

use App\CatalogBusiness;
use Illuminate\Database\Seeder;

class CatalogBusinessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogBusiness::create(['name' => 'Age Solo Inc.',
            'password' => bcrypt('Rulatia12'),
            'email' => 'admin@agesolo.com',
            'first_name' => 'Pierre',
            'last_name' => 'Le Pen',
            'enterprise' => true,
            'status' => 'active']);

        CatalogBusiness::create(['name' => 'Freddie Bravo',
            'password' => bcrypt('Rulatia12'),
            'email' => 'fred@freddiebravo.com',
            'first_name' => 'Fredrick',
            'last_name' => 'Boatman',
            'enterprise' => false,
            'status' => 'active']);

        CatalogBusiness::create(['name' => 'Jet Mart',
            'password' => bcrypt('Rulatia12'),
            'email' => 'jetmartstore@gmail.com',
            'first_name' => 'Mark',
            'last_name' => 'Jett',
            'enterprise' => false,
            'status' => 'active']);

        CatalogBusiness::create(['name' => 'Ridgeway',
            'password' => bcrypt('Rulatia12'),
            'email' => 'corporate@ridgewaystores.com',
            'first_name' => 'Arthur',
            'last_name' => 'Cranbury',
            'enterprise' => true,
            'status' => 'active']);

        CatalogBusiness::create(['name' => 'Elephant Electronics',
            'password' => bcrypt('Rulatia12'),
            'email' => 'jim@elephant-electronics.com',
            'first_name' => 'James',
            'last_name' => 'Gordon III',
            'enterprise' => false,
            'status' => 'active']);

        CatalogBusiness::create(['name' => "Big Bob's Plumbing",
            'password' => bcrypt('Rulatia12'),
            'email' => 'bobtheplumber@hotmail.com',
            'first_name' => 'Robert',
            'last_name' => 'Jackson',
            'enterprise' => false,
            'status' => 'active']);
    }
}
