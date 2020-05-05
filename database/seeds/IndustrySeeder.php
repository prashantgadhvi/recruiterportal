<?php

use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'industry' => 'Agriculture' ],
            [ 'industry' => 'Automotive' ],
            [ 'industry' => 'Construction' ],
            [ 'industry' => 'Farm' ],
            [ 'industry' => 'Health care industry' ],
            [ 'industry' => 'Heavy duty' ],
            [ 'industry' => 'HR' ],
            [ 'industry' => 'IT' ],
            [ 'industry' => 'Milk industry' ],
            [ 'industry' => 'Office admin' ],
            [ 'industry' => 'Restaurant' ],
            [ 'industry' => 'Sales and Marketing' ],
            [ 'industry' => 'Trucking' ],
			[ 'industry' => 'Immigration Consultants' ]
        ];

        foreach ($data as $row){
            $obj = new \App\Industry();
            $obj->fill($row);
            $obj->save();
        }
    }
}
