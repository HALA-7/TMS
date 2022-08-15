<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable=[
        'title', 'description', 'start_at', 'end_at', 'priority_id', 'status_id', 'task_id','test'
    ];

    protected $hidden=['pivot'];

    public function task(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class,'task_id');
    }

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class,'member_subtask',
            'subtask_id','member_id');
    }

    public function status (): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function priority (): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Priority::class,'priority_id');
    }


}
