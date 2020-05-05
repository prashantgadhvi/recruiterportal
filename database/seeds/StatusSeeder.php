<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'status' => 'Active', 'is_custom' => false],
            [ 'status' => 'Cancelled', 'is_custom' => false],
            [ 'status' => 'Closed', 'is_custom' => false],
            [ 'status' => 'DNC', 'is_custom' => false, 'is_hide' => true],
            [ 'status' => 'Follow up', 'is_custom' => false],
            [ 'status' => 'In progress', 'is_custom' => false],
            [ 'status' => 'LMIA Available', 'is_custom' => false],
            [ 'status' => 'Need to apply for LMIA', 'is_custom' => false],
            [ 'status' => 'Need to apply for SINP', 'is_custom' => false],
            [ 'status' => 'Prospect', 'is_custom' => false],
            [ 'status' => 'SINP Available', 'is_custom' => false],
            [ 'status' => 'Others', 'is_custom' => false]
        ];

        foreach ($data as $row){
            $obj = new \App\Status();
            $obj->fill($row);
            $obj->save();
        }
    }
}
