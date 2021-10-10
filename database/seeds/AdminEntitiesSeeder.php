<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminEntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_entities')->insert([
            [
                'name' => '模型表',
                'table_name' => 'admin_entities',
                'description' => '模型表',
            ],
            [
                'name' => '模型字段表',
                'table_name' => 'admin_entity_fields',
                'description' => '模型字段表',
            ]
        ]);
    }
}
