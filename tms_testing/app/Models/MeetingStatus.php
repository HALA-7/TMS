<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingStatus extends Model
{
    use HasFactory;
    protected $fillable=['name'];

    public const UpComing=1;
    public const Finished=2;
    public const Delayed=3;

    public function meetings()
    {
        return $this->hasMany(Meeting::class,'meeting_status_id');
    }

}
