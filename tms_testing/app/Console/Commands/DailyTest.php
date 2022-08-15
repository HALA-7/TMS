<?php

namespace App\Console\Commands;

use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use App\Notifications\SubTaskRemenderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DailyTest extends Command
{

  /*  public function __construct()
    {
        parent::__construct();
    }
*/

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to update the task status automatically';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   // $bool1=0;
        //$bool2=0;

        // get all subtask from database and test its status
        // if it is not completed and it riches the (after) end date
        // and the base task is not riches to the end date
        // make it as late
        // make the status of subtask as MISSED

        //-----------------------------------THIS FOR UPDATE SUBTASK STATUS----------------------------
        $subtask=Subtask::query()->get();
        foreach($subtask as $s)
        {
            $show_task=DB::table('tasks')
                //->select('end_date')
                ->where('tasks.id','=',$s->task_id)
                ->value('end_date');

            // subtask rich end-date                 not completed                          task not end yet
            if(\Carbon\Carbon::now() > $s->end_at && $s->status_id != Status::Completed && $show_task > \Carbon\Carbon::now())
            {
                // make subtask as late
                $s->update(['status_id' => Status::Late]);
            }
            // subtask rich end-date                 not completed                          task end
            else if(\Carbon\Carbon::now() > $s->end_at && $s->status_id != Status::Completed && $show_task <Carbon::now())
            {
                //make subtask as missed so cannot update it
                $s->update(['status_id' => Status::Missed]);
            }
        }

        //---------------------------THIS FOR UPDATE TASK STATUS------------------------------

        $all_task= Task::query()->get();
        foreach ($all_task as $tt)
        {
            $completed_st=$tt->subtasks()->where('status_id','=',Status::Completed)->count();
            $all_st=$tt->subtasks()->count();

            //if all subtask completed
            // and we dont rich to the end date of Task yet
            //so the task is COMPLETED
          if($all_st>0) {
              if ($completed_st == $all_st && $tt->end_date >= Carbon::now()) {
                  $tt->update(['status_id' => Status::Completed]);
              }

              if ($completed_st != $all_st && $tt->end_date < Carbon::now())
              {
                  $tt->update(['status_id' => Status::Missed]);
              }

              /*
           1) if($tm != $tm1 && $tt->end_date >Carbon::now())
                 the task will be IN PROGRESS OR TO DO

           2) if($tm == $tm1 && $tt->end_date <Carbon::now())
                 the task will update automatically to COMPLETED

           */
          }//end if
            else if($all_st==0)
            {
                if ($tt->end_date < Carbon::now()) {
                    $tt->update(['status_id' => Status::Missed]);
                }
            }


        }//end for


        echo 'TEST IF DONE';

    }
}
