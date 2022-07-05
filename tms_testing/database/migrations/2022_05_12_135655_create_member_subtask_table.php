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
        Schema::create('member_subtask', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('subtask_id')->constrained('subtasks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

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
        Schema::dropIfExists('member_subtask');
    }
};
