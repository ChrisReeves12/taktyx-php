<?php

use App\CatalogService;
use Illuminate\Database\Seeder;

class CatalogServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogService::create([
            'name' => 'Justice Supercomputer',
            'address_id' => 2,
            'catalog_business_id' => 1,
            'catalog_sub_category_id' => 6,
            'image_id' => 2,
            'email' => 'service@justice-supercomputer.com',
            'first_name' => 'Mark',
            'last_name' => 'Oldman',
            'keywords' => "computer, networking, repair, supercomputer, gwinnett",
            'status' => 'open'
        ]);

        CatalogService::create([
            'name' => 'Rocketfuel Gaming Machines',
            'address_id' => 3,
            'catalog_business_id' => 1,
            'catalog_sub_category_id' => 8,
            'image_id' => 3,
            'email' => 'support@rocketfuelcomputer.net',
            'first_name' => 'Gerald',
            'last_name' => 'Petersburg',
            'keywords' => "computer, gaming, repair, build, atlanta",
            'status' => 'open'
        ]);

        CatalogService::create([
        'name' => "Freddie Bravo's HVAC Supercenter",
        'address_id' => 4,
        'catalog_business_id' => 2,
        'catalog_sub_category_id' => 4,
        'image_id' => 4,
        'email' => 'fred@freddiebravo.com',
        'first_name' => 'Frederick',
        'last_name' => 'Boatman',
        'keywords' => "hvac, store, heaters, air, cheap",
        'status' => 'open']);

        CatalogService::create([
            'name' => "Jet Mart Atlanta",
            'address_id' => 5,
            'catalog_business_id' => 3,
            'catalog_sub_category_id' => 2,
            'image_id' => 5,
            'email' => 'jetmartstore@gmail.com',
            'first_name' => 'Mark',
            'last_name' => 'Jett',
            'keywords' => "electrical, store, parts, repair",
            'status' => 'open']);

        CatalogService::create([
            'name' => "Ridgeway",
            'address_id' => 6,
            'catalog_business_id' => 4,
            'catalog_sub_category_id' => 3,
            'image_id' => 6,
            'email' => 'jill@ridgewayduluth.com',
            'first_name' => 'Jill',
            'last_name' => 'Valentine',
            'keywords' => "home improvement, atlanta, utilities, carpentry, duluth",
            'status' => 'open']);

        CatalogService::create([
            'name' => "Elephant Electronics",
            'address_id' => 7,
            'catalog_business_id' => 5,
            'catalog_sub_category_id' => 2,
            'image_id' => 7,
            'email' => 'jim@elephant-electronics.com',
            'first_name' => 'James',
            'last_name' => 'Gordon III',
            'keywords' => "car audio, sound, tv, home theater",
            'status' => 'closed']);

        CatalogService::create([
            'name' => "Big Bob's Plumbing",
            'address_id' => 8,
            'catalog_business_id' => 6,
            'catalog_sub_category_id' => 1,
            'image_id' => 8,
            'email' => 'bobtheplumber@hotmail.com',
            'first_name' => 'Robert',
            'last_name' => 'Jackson',
            'keywords' => "plumbing, atlanta, gwinnett",
            'status' => 'busy']);
    }
}
