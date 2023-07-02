<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $visitor = Role::Where('name','role_visitor')->first();
        $admin = Role::Where('name','role_admin')->first();

        $user1 = new User();
        $user1->name = 'kodexadmin';
        $user1->email = 'kodexadmin@example.com';
        $user1->password = bcrypt('kodexMahallo@#15adM1n');
        $user1->save();
        $user1->roles()->attach($visitor);

        $user2 = new User();
        $user2->name = 'kodexadmin2';
        $user2->email = 'kodexadmin2@example.com';
        $user2->password = bcrypt('kodexMahallo@#15adM1n');
        $user2->save();
        $user2->roles()->attach($admin);
    }
}
