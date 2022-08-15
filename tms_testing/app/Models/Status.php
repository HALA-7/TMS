<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Status extends Model
{
    use HasFactory;
    protected $fillable=[
     'name'
    ];

    public const Completed=1;
        public const On_Progress=2;
    public const Missed=3;
    public const To_DO=4;
    public const Late=5;

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class,'status_id');

    }

    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtask::class,'status_id');
    }




}
