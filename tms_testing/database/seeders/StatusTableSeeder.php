<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::query()->create([
                    'id'=>1,
                    'name'=>'Completed',
                ]);

        Status::query()->create([
                    'id'=>2,
                    'name'=>'In_Progress',
                ]);

        Status::query()->create([
                    'id'=>3,
                    'name'=>'Missed',
               ]);

        Status::query()->create([
            'id'=>4,
            'name'=>'To_DO',
        ]);

        // this is for subtask
        Status::query()->create([
            'id'=>5,
            'name'=>'Late',
        ]);
    }
}
