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

//____________________________Admin API_________________________________________

    // TEAM API
    Route::prefix('admin/department')->group(function (){
    Route::post('/add',[App\Http\Controllers\admin\TeamController::class,'store']);
    Route::post('/update/{id}',[App\Http\Controllers\admin\TeamController::class,'update']);
    Route::delete('/delete/{id}',[App\Http\Controllers\admin\TeamController::class,'destroy']);
    Route::get('/show/team',[App\Http\Controllers\admin\TeamController::class,'index']);
    Route::get('/show/team/{id}',[App\Http\Controllers\admin\TeamController::class,'show']);//all team

    });

    // USER API
    Route::prefix('admin/user')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\UserController::class, 'store']);
        Route::put('/update/{user}', [\App\Http\Controllers\admin\UserController::class, 'update']);
        Route::delete('/delete/{user}', [\App\Http\Controllers\admin\UserController::class, 'destroy']);
        Route::get('/show/users',[\App\Http\Controllers\admin\UserController::class, 'index']);

    });

    // TASK API
    Route::prefix('admin/task')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\TaskController::class, 'store']);
        Route::put('/update/{task}', [\App\Http\Controllers\admin\TaskController::class, 'update']);
        Route::delete('/delete/{task}', [\App\Http\Controllers\admin\TaskController::class, 'destroy']);
        Route::get('/show/tasks',[\App\Http\Controllers\admin\TaskController::class, 'index']);

    });

    //MEETING API
    Route::prefix('admin/meeting')->group(function () {
        Route::post('/add', [\App\Http\Controllers\admin\MeetingController::class, 'store']);
        Route::post('/update/{meet}', [\App\Http\Controllers\admin\MeetingController::class, 'update']);
        Route::delete('/delete/{meet}', [\App\Http\Controllers\admin\MeetingController::class, 'destroy']);
        Route::get('/show/meetings',[\App\Http\Controllers\admin\MeetingController::class, 'index']);
    });

   //_____________________________Leader API________________________________________

        Route::prefix('leader')->group(function () {
        // LEADER PROFILE API
        Route::post('/create', [\App\Http\Controllers\teamleader\LeaderController::class, 'store']);
        Route::post('/update/{leader}', [\App\Http\Controllers\teamleader\LeaderController::class, 'update']);
        Route::get('/show/leaders',[\App\Http\Controllers\teamleader\LeaderController::class, 'index']);
        Route::get('/show/{id}',[\App\Http\Controllers\teamleader\LeaderController::class, 'show']);

        Route::prefix('task/{task}')->group(function () {

        // SUBTASK API
        Route::post('/subtask/create',[\App\Http\Controllers\teamleader\SubtaskController::class,'store']);
        Route::post('/subtask/update/{subtask}',[\App\Http\Controllers\teamleader\SubtaskController::class,'update']);
        Route::delete('/subtask/delete/{subtask}',[\App\Http\Controllers\teamleader\SubtaskController::class,'destroy']);

            });

    });

    //________________________Member API_________________________________________
    Route::prefix('member')->group(function () {

        // MEMBER PROFILE API
        Route::post('/create', [\App\Http\Controllers\teammember\MemberController::class, 'store']);
        Route::post('/edit/{member}', [\App\Http\Controllers\teammember\MemberController::class, 'update']);
        Route::get('/show/members',[\App\Http\Controllers\teammember\MemberController::class, 'index']);
        Route::get('/show/{id}',[\App\Http\Controllers\teammember\MemberController::class, 'show']);

        Route::put('/task/{task}/subtask/update/{subtask}',[\App\Http\Controllers\teammember\SubtaskController::class,'update']);


    });

    //COMMENT  AND ATTACHMENT API
    Route::prefix('/task/{task}')->group(function () {
        Route::post('/comment/add',[\App\Http\Controllers\CommentController::class,'store']);
        Route::put('/comment/edit/{comment}',[\App\Http\Controllers\CommentController::class,'update']);
        Route::delete('/comment/delete/{comment}',[\App\Http\Controllers\CommentController::class,'destroy']);

        Route::post('/attachment/add',[\App\Http\Controllers\AttachmentController::class,'store']);
        Route::post('/attachment/edit/{attachment}',[\App\Http\Controllers\AttachmentController::class,'update']);
        Route::delete('/attachment/delete/{attachment}',[\App\Http\Controllers\AttachmentController::class,'destroy']);
    });

    Route::get('/show1',[\App\Http\Controllers\ShowController::class, 'state1']);
    Route::get('/show2',[\App\Http\Controllers\ShowController::class, 'state2']);
    Route::get('/show3',[\App\Http\Controllers\ShowController::class, 'state3']);
});

// NOT AUTH FOR TESTING

Route::get('/all/user/{id}',[\App\Http\Controllers\admin\TeamController::class,'show']);
