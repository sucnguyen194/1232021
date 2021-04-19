<?php
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\User::create([
            'name' => 'Spaussio',
            'account' => 'spaussio',
            'email' => 'spaussio@gmail.com',
            'password' => sha1(md5('123@123')),
            'address' => 'Hà Đông, Hà Nội',
            'phone' => '0965688533',
            'lever' => 1
        ]);

        \App\Models\User::create([
            'name' => 'Admin',
            'account' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => sha1(md5('123@123')),
            'address' => 'Hà Đông, Hà Nội',
            'phone' => '0965688533',
            'lever' => 2
        ]);
    }
}
