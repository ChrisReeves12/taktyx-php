<?php

use App\CatalogSubCategory;
use Illuminate\Database\Seeder;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CatalogSubCategory::create(['name' => 'Plumbing', 'catalog_category_id' => 1]);
		CatalogSubCategory::create(['name' => 'Electrical', 'catalog_category_id' => 1]);
		CatalogSubCategory::create(['name' => 'Carpentry and Repair', 'catalog_category_id' => 1]);
		CatalogSubCategory::create(['name' => 'HVAC', 'catalog_category_id' => 1]);
		CatalogSubCategory::create(['name' => 'Plumbing', 'catalog_category_id' => 1]);
		CatalogSubCategory::create(['name' => 'Computer Networking', 'catalog_category_id' => 2]);
		CatalogSubCategory::create(['name' => 'Computer Repair', 'catalog_category_id' => 2]);
		CatalogSubCategory::create(['name' => 'Computer Building and Installation', 'catalog_category_id' => 2]);
		CatalogSubCategory::create(['name' => 'Computer Programming', 'catalog_category_id' => 2]);
		CatalogSubCategory::create(['name' => 'Web Development', 'catalog_category_id' => 2]);
    }
}
