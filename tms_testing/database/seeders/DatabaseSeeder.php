<?php

namespace Database\Seeders;

use App\Models\MeetingStatus;
use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RoleTableSeeder::class,UserTableSeeder::class,
            StatusTableSeeder::class,MeetingStatusTableSeeder::class,PriorityTableSeeder::class);
    }
}
