<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
           // $table->string('username')->unique();
            $table->text('img_profile')->nullable();
            $table->tinyText('phone');
            $table->text('contact'); // maybe zoom
            $table->mediumText('education');
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnDelete()
                 ->cascadeOnUpdate();
            //$table->text('brief');
          //  $table->foreignId('department_id')->constrained('departments')
            //    ->cascadeOnUpdate()
              //  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
};
