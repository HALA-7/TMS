<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('start_at');
            $table->date('end_at');
            $table->foreignId('priority_id')->constrained('priorities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('statuses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('task_id')->constrained('tasks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('subtasks');
    }
};
