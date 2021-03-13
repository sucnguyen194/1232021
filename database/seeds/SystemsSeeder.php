<?php

use Illuminate\Database\Seeder;

class SystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SystemsModule::create([
            'name' => 'Bảng điều khiển',
            'route' => 'admin.dashboard',
            'type' =>  'dashboard',
            'parent_id'=> 0,
            'icon' => '<i class="fa fa-home"></i>',
            'sort' => 9999
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Module Systems',
            'route' => 'admin.systems.index',
            'type' =>   'ModuleSystems',
            'parent_id'=> 0,
            'icon' => '<i class="fa fa-users" aria-hidden="true"></i>',
            'sort' => 9999
        ]);
    }
}
