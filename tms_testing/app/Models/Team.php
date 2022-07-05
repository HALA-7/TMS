<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable=['name'];

   // public $with = ['leader','members'];


    public function users()
    {
        return $this->hasMany(User::class,'team_id');
    }
    public function leader(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(Leader::class,User::class,
            'team_id','user_id','id','id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Member::class,User::class,
        'team_id','user_id','id','id');
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class,'team_id');
    }

}
