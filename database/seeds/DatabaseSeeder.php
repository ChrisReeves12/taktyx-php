<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);
		$this->call(CategoriesTableSeeder::class);
		$this->call(SubCategoriesTableSeeder::class);
		$this->call(ImagesSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(AddressesSeeder::class);
		$this->call(UsersSeeder::class);
		$this->call(CatalogBusinessesSeeder::class);
		$this->call(CatalogServicesSeeder::class);
		$this->call(EmployeesSeeder::class);
    }
}
