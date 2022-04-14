<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Registry;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/index.html', function () {
    return view('auth/login');
});

Route::get('/register', function () {
    return view('welcome');
});

Route::get('/user_logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('user_logout');


/*
|--------------------------------------------------------------------------
| Pre-defined authentication routes
|--------------------------------------------------------------------------
*/

Auth::routes();


/*  These routes ony permitted if logged in */

Route::group(['middleware' => 'auth'], function () {

  //First login? If so, redirect to the initialisation pages if any of the following are true
  //  a) The registry table hasn't been created yet
  //  b) The registry table has been created, and the 'first-run' value is set to true
  $registry = new Registry;
  if ( (!Schema::hasTable('registry')) || ($registry->getValue('first-run')) ) {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'install'])->name('home');
    Route::get('/install/pg2', [App\Http\Controllers\HomeController::class, 'installPage2'])->name('install_page2');
    Route::get('/install/pg3', [App\Http\Controllers\HomeController::class, 'installPage3'])->name('install_page3');
    Route::get('/install/pg4', [App\Http\Controllers\HomeController::class, 'installPage4'])->name('install_page4');
    Route::get('/install/pg5', [App\Http\Controllers\HomeController::class, 'installPage5'])->name('install_page5');
    Route::get('/install/pg6', [App\Http\Controllers\HomeController::class, 'installPage6'])->name('install_page6');
  }
  else
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

  /*
  |--------------------------------------------------------------------------
  | Student routes
  |--------------------------------------------------------------------------
  */

  Route::get('/students', [App\Http\Controllers\StudentsController::class, 'index'])->name('students.index');
//  Route::get('/students/{student}/view', [App\Http\Controllers\StudentsController::class, 'view'])->name('students.view');
  Route::get('/students/{student}/edit', [App\Http\Controllers\StudentsController::class, 'edit'])->name('students.edit');
  Route::put('/students/{student}/update', [App\Http\Controllers\StudentsController::class, 'update'])->name('students.update');
  Route::get('/students/{student}/delete', [App\Http\Controllers\StudentsController::class, 'delete'])->name('students.delete');
  Route::put('/students/store', [App\Http\Controllers\StudentsController::class, 'store'])->name('students.store');

  /*
  |--------------------------------------------------------------------------
  | Year group routes
  |--------------------------------------------------------------------------
  */


  /*
  |--------------------------------------------------------------------------
  | Teaching group routes
  |--------------------------------------------------------------------------
  */

  Route::get('/teaching-groups', [App\Http\Controllers\TeachingGroupsController::class, 'index'])->name('teaching_groups.index');
  Route::get('/teaching-groups/{group}/edit', [App\Http\Controllers\TeachingGroupsController::class, 'edit'])->name('teaching_groups.edit');
  Route::get('/teaching-groups/{group}/delete', [App\Http\Controllers\TeachingGroupsController::class, 'delete'])->name('teaching_groups.delete');
  Route::get('/teaching-groups/{group}/remove-student/{student}', [App\Http\Controllers\TeachingGroupsController::class, 'removeStudent'])->name('teaching_groups.remove_student');
  Route::put('/teaching-groups/store', [App\Http\Controllers\TeachingGroupsController::class, 'store'])->name('teaching_groups.store');
  Route::put('/teaching-groups/update', [App\Http\Controllers\TeachingGroupsController::class, 'update'])->name('teaching_groups.update');
  Route::get('/teaching-groups/get/{group}', [App\Http\Controllers\TeachingGroupsController::class, 'get'])->name('teaching_groups.get');
  Route::get('/teaching-groups/{group}/view', [App\Http\Controllers\TeachingGroupsController::class, 'view'])->name('teaching_groups.view');

  /*
  |--------------------------------------------------------------------------
  | Notes routes
  |--------------------------------------------------------------------------
  */

  Route::get('/notes/get/{note}', [App\Http\Controllers\NotesController::class, 'get'])->name('notes.get');
  Route::put('/notes/update', [App\Http\Controllers\NotesController::class, 'update'])->name('notes.update');
  Route::put('/notes/store', [App\Http\Controllers\NotesController::class, 'store'])->name('notes.store');
  Route::put('/notes/delete', [App\Http\Controllers\NotesController::class, 'destroy'])->name('notes.delete');
  Route::put('/notes/own', [App\Http\Controllers\NotesController::class, 'takeOwnership'])->name('notes.own');
  Route::get('/notes/user', [App\Http\Controllers\NotesController::class, 'userNotesIndex'])->name('notes.user.index');

  /*
  |--------------------------------------------------------------------------
  | Cohorts routes
  |--------------------------------------------------------------------------
  */

  Route::get('/cohorts', [App\Http\Controllers\CohortsController::class, 'index'])->name('cohorts.index');
  Route::get('/cohorts/{cohort}/view', [App\Http\Controllers\CohortsController::class, 'view'])->name('cohorts.view');

});






/*
|--------------------------------------------------------------------------
| Admin only routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'role:admin']], function () {

  //--------------------------
  //
  // Users
  //
  //--------------------------

  Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
  Route::put('/users/store', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
  Route::put('/users/update', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
  Route::get('/users/get/{user}', [App\Http\Controllers\UsersController::class, 'get'])->name('users.get');
  Route::put('/users/delete', [App\Http\Controllers\UsersController::class, 'delete'])->name('users.delete');

  //--------------------------
  //
  // Tags
  //
  //--------------------------

  Route::get('/tags', [App\Http\Controllers\TagsController::class, 'index'])->name('tags.index');
  Route::get('/tags/get/{tag}', [App\Http\Controllers\TagsController::class, 'get'])->name('tags.get');
  Route::post('/tags/is-unique', [App\Http\Controllers\TagsController::class, 'isUnique'])->name('tags.is-unique');
  Route::put('/tags/update', [App\Http\Controllers\TagsController::class, 'update'])->name('tags.update');
  Route::put('/tags/store', [App\Http\Controllers\TagsController::class, 'store'])->name('tags.store');
  Route::get('/tags/delete', [App\Http\Controllers\TagsController::class, 'delete'])->name('tags.delete');

  //--------------------------
  //
  // Cohorts
  //
  //--------------------------

  Route::get('/cohorts/manage', [App\Http\Controllers\CohortsController::class, 'manage'])->name('cohorts.manage');
  Route::get('/cohorts/create', [App\Http\Controllers\CohortsController::class, 'create'])->name('cohorts.create');
  Route::put('/cohorts/store', [App\Http\Controllers\CohortsController::class, 'store'])->name('cohorts.store');
  Route::get('/cohorts/{cohort}/edit', [App\Http\Controllers\CohortsController::class, 'edit'])->name('cohorts.edit');
  Route::put('/cohorts/{cohort}/update', [App\Http\Controllers\CohortsController::class, 'update'])->name('cohorts.update');
  Route::get('/cohorts/{cohort}/delete', [App\Http\Controllers\CohortsController::class, 'delete'])->name('cohorts.delete');

  //Cohort conditions
  Route::get('/cohorts/conditions/get/{cohort}', [App\Http\Controllers\ConditionSetController::class, 'get'])->name('cohorts_conditions.get');

  //--------------------------
  //
  // Year groups
  //
  //--------------------------

  Route::get('/year-groups', [App\Http\Controllers\YearGroupController::class, 'index'])->name('year_groups.index');
  Route::get('/year-groups/{group}/view', [App\Http\Controllers\YearGroupController::class, 'view'])->name('year_groups.view');
  Route::get('/year-groups/get/{group}', [App\Http\Controllers\YearGroupController::class, 'get'])->name('year_groups.get');
  Route::put('/year-groups/store', [App\Http\Controllers\YearGroupController::class, 'store'])->name('year_groups.store');
  Route::put('/year-groups/update', [App\Http\Controllers\YearGroupController::class, 'update'])->name('year_groups.update');

  //--------------------------
  //
  // Settings
  //
  //--------------------------

  Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
  Route::post('/settings/update', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

  //--------------------------
  //
  // Back ups
  //
  //--------------------------

  Route::get('/backup', [App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
  Route::get('/backup/download/{file}', [App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
  Route::get('/backup/restore/{file}', [App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
  Route::get('/backup/delete/{file}', [App\Http\Controllers\BackupController::class, 'delete'])->name('backup.delete');
  Route::get('/backup/run', [App\Http\Controllers\BackupController::class, 'run'])->name('backup.run');

});

/*
|--------------------------------------------------------------------------
| End of admin only routes
|--------------------------------------------------------------------------
*/
