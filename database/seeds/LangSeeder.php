<?php
use Illuminate\Database\Seeder;

class LangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Lang::create([
            'name' => 'Vietnamese',
            'value' => 'vn',
            'status' => 1,
            'sort' => 1
        ]);

        \App\Models\Lang::create([
            'name' => 'English',
            'value' => 'en',
            'status' => 0,
            'sort' => 2
        ]);

    }
}
