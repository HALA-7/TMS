<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingStatus extends Model
{
    use HasFactory;
    protected $fillable=['name'];

    public function meetings()
    {
        return $this->hasMany(Meeting::class,'meeting_status_id');
    }

}
