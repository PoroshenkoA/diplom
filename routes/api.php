<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/notifications', 'API\NotificationsController@index');
Route::middleware('auth:api')->post('/hideNote', 'API\NotificationsController@hideNote');
Route::middleware('auth:api')->get('/hideAllNotes', 'API\NotificationsController@hideAllNotes');

Route::middleware('auth:api')->get('/adminUserNotes/{name}', 'API\AdminController@userNotes');
Route::middleware('auth:api')->get('/adminUser/{name}', 'API\AdminController@user');
Route::middleware('auth:api')->get('/adminDateNotes/{date}', 'API\AdminController@dateNotes');
Route::middleware('auth:api')->get('/adminMakeWorks', 'API\AdminController@makeWorks');
Route::middleware('auth:api')->post('/createNewQues', 'API\AdminController@createNewQues');
Route::middleware('auth:api')->post('/editRate', 'API\AdminController@editRate');
Route::middleware('auth:api')->post('/editProt', 'API\AdminController@editProt');
Route::middleware('auth:api')->post('/editRec', 'API\AdminController@editRec');
Route::middleware('auth:api')->post('/adminChangeDate', 'API\AdminController@editDate');
Route::middleware('auth:api')->post('/adminDelQues', 'API\AdminController@delQues');
Route::middleware('auth:api')->post('/editLeaderLoad', 'API\AdminController@editLeaderLoad');


Route::middleware('auth:api')->get('/work', 'API\StudentController@work');
Route::middleware('auth:api')->get('/getLeaders', 'API\StudentController@leaders');
Route::middleware('auth:api')->get('/requests', 'API\StudentController@requests');
Route::middleware('auth:api')->post('/createRequest', 'API\StudentController@createRequest');
Route::middleware('auth:api')->post('/studChangeThemeEn', 'API\StudentController@studChangeThemeEn');
Route::middleware('auth:api')->post('/studEditRealPages', 'API\StudentController@studEditRealPages');
Route::middleware('auth:api')->post('/studEditGPages', 'API\StudentController@studEditGPages');
Route::middleware('auth:api')->post('/studChangeThemeUkr', 'API\StudentController@studChangeThemeUkr');
Route::middleware('auth:api')->post('/studChangeDate', 'API\StudentController@studChangeDate');
Route::middleware('auth:api')->get('/studGetAvDates', 'API\StudentController@getDates');
Route::middleware('auth:api')->post('/studDelRev', 'API\StudentController@DelRev');
Route::middleware('auth:api')->post('/studAddNewRev', 'API\StudentController@addRev');
Route::middleware('auth:api')->post('/studSendFile', 'API\StudentController@store');


Route::middleware('auth:api')->get('/getStudents', 'API\LeaderController@students');
Route::middleware('auth:api')->get('/getLeaderWorks', 'API\LeaderController@works');
Route::middleware('auth:api')->get('/getLeaderType', 'API\LeaderController@getType');
Route::middleware('auth:api')->post('/leaderChangeThemeEn', 'API\LeaderController@leaderChangeThemeEn');
Route::middleware('auth:api')->post('/leaderChangeThemeUkr', 'API\LeaderController@leaderChangeThemeUkr');
Route::middleware('auth:api')->post('/leaderAddNewRev', 'API\LeaderController@addRev');
Route::middleware('auth:api')->post('/leaderEditRealPages', 'API\LeaderController@EditRealPages');
Route::middleware('auth:api')->post('/leaderDelRev', 'API\LeaderController@DelRev');
Route::middleware('auth:api')->post('/leaderEditGPages', 'API\LeaderController@EditGPages');
Route::middleware('auth:api')->post('/leaderSendFile', 'API\LeaderController@store');

Route::middleware('auth:api')->get('/getExaminerWorks', 'API\ExaminerController@works');

Route::middleware('auth:api')->post('/deleteRequest', 'API\StudentController@deleteRequest');
Route::middleware('auth:api')->post('/deleteWork', 'API\AdminController@deleteWork');
Route::middleware('auth:api')->post('/makeNot', 'API\NotificationsController@makeNot');
Route::middleware('auth:api')->post('/makeAdminNote', 'API\NotificationsController@makeAdminNote');
Route::middleware('auth:api')->post('/makeExaminerNote', 'API\NotificationsController@makeExaminerNote');
Route::middleware('auth:api')->post('/editPrior', 'API\StudentController@editPrior');
Route::middleware('auth:api')->post('/adminNewUsername', 'API\AdminController@newUsername');
Route::middleware('auth:api')->post('/adminNewEmail', 'API\AdminController@newEmail');
Route::middleware('auth:api')->post('/updateLeaderPriority', 'API\LeaderController@updateLeaderPriority');
