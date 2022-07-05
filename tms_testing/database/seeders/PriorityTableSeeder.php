<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriorityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Priority::query()->create([
            'id'=>1,
            'name'=>'High',
        ]);

        Priority::query()->create([
            'id'=>2,
            'name'=>'Middle',
        ]);

        Priority::query()->create([
            'id'=>3,
            'name'=>'Low',
        ]);
    }
}
