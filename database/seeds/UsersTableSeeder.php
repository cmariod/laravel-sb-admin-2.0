<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
                  'name' => 'Mario Dinata',
                  'email' => 'mario@texture.sg',
                  'password' => bcrypt('123456'),
                  'level' => 'Admin',
              ]);
    }
}
