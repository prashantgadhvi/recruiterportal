<?php

use Illuminate\Database\Seeder;

class RequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'requisition' => 'Follow up', 'is_custom' => false],
            [ 'requisition' => 'Pending', 'is_custom' => false],
            [ 'requisition' => 'Received', 'is_custom' => false],
            [ 'requisition' => 'Others', 'is_custom' => false]
        ];

        foreach ($data as $row){
            $obj = new \App\Requisition();
            $obj->fill($row);
            $obj->save();
        }
    }
}
