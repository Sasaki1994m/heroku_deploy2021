<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'sample001',
            'login_id' => 'sampletest01',
            'password' => bcrypt('sample001'),
            'created_at' => '2021-03-01 09:00:00',
            'role' => '3',
        ]);
        DB::table('users')->insert([
            'name' => 'sample002',
            'login_id' => 'sampletest02',
            'password' => bcrypt('sample002'),
            'created_at' => '2021-03-02 09:00:00',
            'role' => '5',
        ]);
        DB::table('users')->insert([
            'name' => 'sample003',
            'login_id' => 'sampletest03',
            'password' => bcrypt('sample003'),
            'created_at' => '2021-03-03 09:00:00',
            'role' => '6',
        ]);
        DB::table('users')->insert([
            'name' => 'sample004',
            'login_id' => 'sampletest04',
            'password' => bcrypt('sample004'),
            'created_at' => '2021-03-04 09:00:00',
            'role' => '1',
        ]);
        DB::table('users')->insert([
            'name' => 'sample005',
            'login_id' => 'sampletest05',
            'password' => bcrypt('sample005'),
            'created_at' => '2021-03-05 09:00:00',
            'role' => '10',
        ]);
    }
}
