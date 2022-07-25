<?php

namespace App\Console\Commands;

use App\Models\Status;
use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        // if it is not completed and the rich the (after) end date
        // make the status of subtask as MISSED
        $subtask=Subtask::query()->get();
        foreach($subtask as $s)
        {
            if (\Carbon\Carbon::now() > $s->end_at && $s->status_id != Status::Completed)
              {$subtask->update(['status_id' => Status::Missed]);}
           // $bool1=1;
        }


        $all_task= Task::query()->get();
        foreach ($all_task as $tt)
        {
            $tm=$tt->subtasks()->where('status_id','=',Status::Completed)->count();
            $tm1=$tt->subtasks()->count();

            //if all subtask completed
            // and we dont rich to the end date of Task yet
            //so the task is COMPLETED

            if($tm ==$tm1 && $tt->end_date >Carbon::now())
            {$tt->update(['status_id'=>Status::Completed]);}

            else if($tm != $tm1 && $tt->end_date < Carbon::now())
            {$tt->update(['status_id'=>Status::Missed]);}

            /*
         1) if($tm != $tm1 && $tt->end_date >Carbon::now())
               the task will be IN PROGRESS OR TO DO

         2) if($tm == $tm1 && $tt->end_date <Carbon::now())
               the task will update automatically to COMPLETED

         */

       // $bool2=1;
        }
      //  if($bool1 && $bool2)
        echo 'TEST IF DONE';

    }
}
