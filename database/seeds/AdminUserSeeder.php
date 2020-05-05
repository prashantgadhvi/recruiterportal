<?php

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->name     = 'Admin';
        $user->email    = 'prashantgadhvi111@gmail.com';
        $user->type     = 1;
        $user->password = bcrypt('Prashant123');
        $user->save();
    }
}
