<?php

use App\CatalogCategory;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		CatalogCategory::create(['name' => 'Utilities']);
        CatalogCategory::create(['name' => 'Computer Technology']);;
    }
}
