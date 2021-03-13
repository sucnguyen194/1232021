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
        \App\Models\SiteSetting::create([
            'name' => 'Trang chủ',
            'lang' => 'vn'
        ]);

        \App\Models\SiteSetting::create([
            'name' => 'Home',
            'lang' => 'en'
        ]);
    }
}
