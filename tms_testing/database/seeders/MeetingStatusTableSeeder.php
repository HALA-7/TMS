<?php

namespace Database\Seeders;

use App\Models\MeetingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class
MeetingStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeetingStatus::query()->create([
            'id'=>1,
            'name'=>'Up Coming',
        ]);

        MeetingStatus::query()->create([
            'id'=>2,
            'name'=>'Finished',
        ]);

        MeetingStatus::query()->create([
            'id'=>3,
            'name'=>'Delayed',
        ]);
    }
}
