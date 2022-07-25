<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable=[
        //'title',
        //'discussion_point',
        'meeting_date',
        'start_at',
        'meeting_statuses_id',
        ];
    protected  $hidden=['pivot'];
    public const UpComing= 1;
    public const Finished=2;
    public const Delayed=3;

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'meeting_user',
            'meeting_id','user_id');
    }



    public function meetingstatuses (): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MeetingStatus::class,'meeting_statuses_id');
    }



}
