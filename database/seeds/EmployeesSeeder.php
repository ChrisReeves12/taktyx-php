<?php

use App\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::create([
            'email' => 'jcrew19@gmail.com',
            'first_name' => 'Jay',
            'last_name' => 'Higginbothom',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 1,
                        'employee_id' => 1,
                        'admin' => true,
                        'status' => 'active',
                        'image_id' => 9
        ]);


        Employee::create([
            'email' => 'allendoe@hotmail.com',
            'first_name' => 'Allen',
            'last_name' => 'Doe',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 1,
                'employee_id' => 2,
                'admin' => false,
                'status' => 'active',
                'image_id' => 10
            ]);

        Employee::create([
            'email' => 'andreamck102@yahoo.com',
            'first_name' => 'Andrea',
            'last_name' => 'McKellen',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 1,
                'employee_id' => 3,
                'admin' => false,
                'status' => 'active',
                'image_id' => 11
            ]);

        Employee::create([
            'email' => 'pbyrd@ridgewayduluth.com',
            'first_name' => 'Patricia',
            'last_name' => 'Byrd',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 5,
                'employee_id' => 4,
                'admin' => true,
                'status' => 'active',
                'image_id' => 12
        ]);

        Employee::create([
            'email' => 'dragonpunch129@hotmail.com',
            'first_name' => 'Darius',
            'last_name' => 'Rhodes',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 5,
                'employee_id' => 5,
                'admin' => false,
                'status' => 'active',
                'image_id' => 13
            ]);

        Employee::create([
            'email' => 'tlexington1@gmail.com',
            'first_name' => 'Trevor',
            'last_name' => 'Lexington',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 5,
                'employee_id' => 6,
                'admin' => false,
                'status' => 'active',
                'image_id' => 14
            ]);

        Employee::create([
            'email' => 'sbtoohot64@gmail.com',
            'first_name' => 'Sarah',
            'last_name' => 'Bridges',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 5,
                'employee_id' => 7,
                'admin' => false,
                'status' => 'active',
                'image_id' => 15
            ]);

        Employee::create([
            'email' => 'orangemm3@gmail.com',
            'first_name' => 'Manuel',
            'last_name' => 'Orange',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 5,
                'employee_id' => 8,
                'admin' => false,
                'status' => 'active',
                'image_id' => 16
            ]);

        Employee::create([
            'email' => 'michaeljhill@rocketfuelcomputer.net',
            'first_name' => 'Michael J.',
            'last_name' => 'Hill',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 2,
                'employee_id' => 9,
                'admin' => true,
                'status' => 'active',
                'image_id' => 17
            ]);

        Employee::create([
            'email' => 'ashley.townsend@rocketfuelcomputer.net',
            'first_name' => 'Ashley',
            'last_name' => 'Townsend',
            'password' => bcrypt('Rulatia12')
        ]);

        DB::table('catalog_service_employee')
            ->insert(['catalog_service_id' => 2,
                'employee_id' => 10,
                'admin' => false,
                'status' => 'active',
                'image_id' => 18
            ]);
    }
}
