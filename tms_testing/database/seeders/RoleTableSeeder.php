<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder; //i add it
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB; // i add it
use Illuminate\Support\Str;  // i add it
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insert( [
            'position'=>'admin'
        ]);

      DB::table('roles')->insert([
         'position'=>'team_leader'
      ]);
         DB::table('roles')->insert( [
              'position'=>'team_member'
          ]);




    }
}
