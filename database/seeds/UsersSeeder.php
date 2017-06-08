<?php

use App\Customer;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create([
            'username' => 'CraigReeves1230',
            'email' => 'reevescd1@gmail.com',
            'status' => 'active',
            'address_id' => 1,
            'image_id' => 1,
            'password' => bcrypt('Rulatia12'),
        ]);

    }
}
