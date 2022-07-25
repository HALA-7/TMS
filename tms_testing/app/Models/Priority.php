<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;
    protected $fillable=[
    'name'
    ];

    public const High=1;
    public const Middle=2;
    public const Low=3;


    public function  subtasks()
    {
        return $this->hasMany(Subtask::class,'priority_id');

    }
}
