<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::query()->create([
            'id'=>1,
            'first_name'=>'hala',
            'last_name'=>'ali',
            'email'=>'halaali.sy22@gmail.com',
            'employee_identical'=>'1531',
            'password'=>bcrypt('123456789'),
            'role_id'=>1,
            'team_id'=>null,
            'remember_token'=>true
        ]);



    }

}
