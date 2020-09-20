<?php

use App\userType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        userType::create([
            'name' => 'user',
            'table' => 'users',
        ]);
        userType::create([
            'name' => 'provider',
            'table' => 'users',
            'status'=>0
        ]);
        userType::create([
            'name' => 'writer',
            'table' => 'users',
            'parent_id'=>2
        ]);
        userType::create([
            'name' => 'studio',
            'table' => 'users',
            'parent_id'=>2
        ]);
        userType::create([
            'name' => 'superAdmin',
            'table' => 'admins',
            'status'=>0
        ]);

    }
}
