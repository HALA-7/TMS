<?php

use App\Http\Controllers\admin\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test',[\App\Http\Controllers\AuthController::class,'index']);

Route::post('auth/login', [\App\Http\Controllers\AuthController::class, 'login']);



Route::middleware(['auth:api'])->group(callback: function (){


    Route::get('/logout',[\App\Http\Controllers\AuthController::class,'logout']);

//__________________________________Admin API_________________________________________

                   //-----------------TEAM API-------------------//

    Route::prefix('admin/department')->group(function (){
    Route::post('/add',[App\Http\Controllers\admin\TeamController::class,'store']);
    Route::post('/update/{id}',[App\Http\Controllers\admin\TeamController::class,'update']);
    Route::delete('/delete/{id}',[App\Http\Controllers\admin\TeamController::class,'destroy']);
    Route::get('/show/team',[App\Http\Controllers\admin\TeamController::class,'ShowTeams']);//all team
    Route::get('/show/team/{id}',[App\Http\Controllers\admin\TeamController::class,'ShowTeam']);

    });

    //----------------------------------------USER API----------------------------
    Route::prefix('admin/user')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\UserController::class, 'store']);
        Route::put('/update/{user}', [\App\Http\Controllers\admin\UserController::class, 'update']);
        Route::delete('/delete/{user}', [\App\Http\Controllers\admin\UserController::class, 'destroy']);
        Route::get('/show/users',[\App\Http\Controllers\admin\UserController::class, 'ShowUsers']);
        Route::get('/show/user/{user}',[App\Http\Controllers\admin\UserController::class,'ShowUser']);
        Route::get('/show/with/details',[App\Http\Controllers\admin\UserController::class,'ShowUsersDetails']);
    });

    //-----------------------------------TASK API-------------------------------------
    Route::prefix('admin/task')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\TaskController::class, 'store']);
        Route::put('/update/{task}', [\App\Http\Controllers\admin\TaskController::class, 'update']);
        Route::delete('/delete/{task}', [\App\Http\Controllers\admin\TaskController::class, 'destroy']);
        //Route::get('/show/tasks',[\App\Http\Controllers\admin\TaskController::class, 'index']);
        //Route::get('/show/task/{id}',[\App\Http\Controllers\admin\TaskController::class, 'show']);

    });

    //------------------------------------MEETING API-------------------------------------

    Route::prefix('admin/meeting')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\MeetingController::class, 'store']);
        Route::post('/update/{meet}', [\App\Http\Controllers\admin\MeetingController::class, 'update']);
        Route::delete('/delete/{meet}', [\App\Http\Controllers\admin\MeetingController::class, 'destroy']);
      //  Route::get('/show/meetings',[\App\Http\Controllers\admin\MeetingController::class, 'index']);
       // Route::get('/show/meetings/{id}',[\App\Http\Controllers\admin\MeetingController::class, 'show']);
    });

   //_____________________________Leader API________________________________________

        Route::prefix('leader')->group(function () {
        // LEADER PROFILE API
        Route::post('/create', [\App\Http\Controllers\teamleader\LeaderController::class, 'store']);
        Route::post('/update/{leader}', [\App\Http\Controllers\teamleader\LeaderController::class, 'update']);
        //Route::get('/show/myMembers',[\App\Http\Controllers\teamleader\LeaderController::class, 'myteam']);
        //Route::get('/show/profile',[\App\Http\Controllers\teamleader\LeaderController::class, 'myprofile']);
        //Route::get('/show/myMeetings',[\App\Http\Controllers\teamleader\LeaderController::class, 'MyMeeting']);


        Route::prefix('task')->group(function () {

        // SUBTASK API
        Route::get('/show/myTask',[\App\Http\Controllers\teamleader\SubtaskController::class, 'MyTask']);
        Route::get('/show/mySubTask',[\App\Http\Controllers\teamleader\SubtaskController::class, 'index']);
        Route::post('/{task}/subtask/create',[\App\Http\Controllers\teamleader\SubtaskController::class,'store']);
        Route::post('/{task}/subtask/update/{subtask}',[\App\Http\Controllers\teamleader\SubtaskController::class,'update']);
        Route::delete('/{task}/subtask/delete/{subtask}',[\App\Http\Controllers\teamleader\SubtaskController::class,'destroy']);
        Route::get('/show/SubTask/{task}',[\App\Http\Controllers\teamleader\SubtaskController::class, 'show']);

            });

    });

    //________________________Member API_________________________________________
    Route::prefix('member')->group(function () {

        // MEMBER PROFILE API
        Route::get('/show/members',[\App\Http\Controllers\teammember\MemberController::class, 'MyTeam']);
        Route::post('/create', [\App\Http\Controllers\teammember\MemberController::class, 'store']);
        Route::post('/edit/{member}', [\App\Http\Controllers\teammember\MemberController::class, 'update']);
        Route::get('/show',[\App\Http\Controllers\teammember\MemberController::class, 'profile']);
        Route::put('/task/{task}/subtask/update/{subtask}',[\App\Http\Controllers\teammember\SubtaskController::class,'update']);
        Route::get('/show/Meetings',[\App\Http\Controllers\teammember\MemberController::class, 'MyMeeting']);
        Route::get('/show/myTask',[\App\Http\Controllers\teammember\SubtaskController::class, 'MyTask']);

    });

    //COMMENT  AND ATTACHMENT API
    Route::prefix('/task')->group(function () {
        Route::post('/{task}/comment/add',[\App\Http\Controllers\CommentController::class,'store']);
        Route::put('/{task}/comment/edit/{comment}',[\App\Http\Controllers\CommentController::class,'update']);
        Route::delete('/{task}/comment/delete/{comment}',[\App\Http\Controllers\CommentController::class,'destroy']);
        Route::get('/show/comments/{id}',[\App\Http\Controllers\CommentController::class, 'show']);

        Route::post('/attachment/add',[\App\Http\Controllers\AttachmentController::class,'store']);
        Route::post('/attachment/edit/{attachment}',[\App\Http\Controllers\AttachmentController::class,'update']);
        Route::delete('/attachment/delete/{attachment}',[\App\Http\Controllers\AttachmentController::class,'destroy']);
        Route::get('/show/attachment/{id}',[\App\Http\Controllers\AttachmentController::class, 'show']);
    });

    //--------------------------------CALENDER-----------------------------------
    Route::post('add/event',[App\Http\Controllers\EventController::class,'store']);
    Route::put('edit/event/{id}',[App\Http\Controllers\EventController::class,'update']);
    Route::delete('delete/event/{id}',[App\Http\Controllers\EventController::class,'destroy']);
    Route::get('show/events',[App\Http\Controllers\EventController::class,'MyEvents']);
    Route::get('show/event/{id}',[App\Http\Controllers\EventController::class,'show']);


    //-----------------------------SHOW CONTROLLER-----------------------------------------------
    Route::get('/show/MyTeam',[\App\Http\Controllers\ShowController::class,'ShowMyTeam']);
    Route::get('/show/MyMeeting',[\App\Http\Controllers\ShowController::class,'ShowMyMeetings']);
    Route::get('/show/MyProfile',[\App\Http\Controllers\ShowController::class,'ShowMyProfile']);
    Route::get('/show/MyTask',[\App\Http\Controllers\ShowController::class,'ShowMyTask']);
    Route::get('/show/MySubTask',[\App\Http\Controllers\ShowController::class,'ShowMySubTask']);
    Route::get('/show/details/{task}',[\App\Http\Controllers\ShowController::class,'Details']);
    Route::post('/search/task',[\App\Http\Controllers\ShowController::class,'TaskSearch']);
    Route::post('/search/subtask',[\App\Http\Controllers\ShowController::class,'SubTaskSearch']);

    //----------------------------------Filtering-------------------------------------------

    Route::get('/show/completed/tasks',[\App\Http\Controllers\FilteringController::class,'CompletedTask']);
    Route::get('/show/progress/tasks',[\App\Http\Controllers\FilteringController::class,'OnProgressTask']);
    Route::get('/show/missed/tasks',[\App\Http\Controllers\FilteringController::class,'MissedTask']);
    Route::get('/show/todo/tasks',[\App\Http\Controllers\FilteringController::class,'To_DoTask']);
    Route::get('/show/completed/subtasks',[\App\Http\Controllers\FilteringController::class,'CompletedSubTask']);
    Route::get('/show/progress/subtasks',[\App\Http\Controllers\FilteringController::class,'ProgressSubTask']);
    Route::get('/show/missed/subtasks',[\App\Http\Controllers\FilteringController::class,'MissedSubTask']);
    Route::get('/show/todo/subtasks',[\App\Http\Controllers\FilteringController::class,'ToDoSubTask']);



    //------------------------------Reporting----------------------------------
    Route::get('/show/report',[\App\Http\Controllers\ReportController::class,'ShowReport']);
    Route::get('/show/statistic',[\App\Http\Controllers\ReportController::class,'ShowStatistic']);
    Route::get('/show/member/statistic',[\App\Http\Controllers\ReportController::class,'ShowMemberStatistic']);






    Route::get('/show1',[\App\Http\Controllers\ShowController::class, 'state1']);
    Route::get('/show2',[\App\Http\Controllers\ShowController::class, 'state2']);
    Route::get('/show3',[\App\Http\Controllers\ShowController::class, 'state3']);
});


