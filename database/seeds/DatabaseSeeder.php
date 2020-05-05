<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUserSeeder::class);
        $this->call(IndustrySeeder::class);
        $this->call(RequisitionSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(CountryStateCitySeeder::class);
    }
}
