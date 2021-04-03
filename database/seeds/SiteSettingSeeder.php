<?php

use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Setting::create([
            'name' => 'Trang chủ',
            'lang' => 'vn'
        ]);

        \App\Models\Setting::create([
            'name' => 'Home',
            'lang' => 'en'
        ]);
    }
}
