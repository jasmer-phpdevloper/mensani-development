<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAthleteController;
use App\Http\Controllers\ApiTherapistController;


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
Route::group(['prefix' => 'Athlete'], function () {
    
Route::post('signup',[ApiAthleteController::class,'signup']);
Route::post('login',[ApiAthleteController::class,'login']); 
Route::post('forget_password',[ApiAthleteController::class,'forget_password']);
Route::post('verify_otp',[ApiAthleteController::class,'verify_otp']);
Route::post('reset_password',[ApiAthleteController::class,'reset_password']);  
});
Route::group(['prefix' => 'Athlete','middleware' => ['jwt.verify']], function () {    
  
Route::post('logout',[ApiAthleteController::class,'logout']);
Route::post('edit_profile',[ApiAthleteController::class,'edit_profile']);
Route::post('view_notification',[ApiAthleteController::class,'view_notification']);
Route::post('home_screen',[ApiAthleteController::class,'home_screen']);
Route::post('view_support',[ApiAthleteController::class,'view_support']);
Route::post('change_password',[ApiAthleteController::class,'change_password']);
Route::post('notification',[ApiAthleteController::class,'notification']);
Route::post('view_todayplans',[ApiAthleteController::class,'view_todayplans']);
Route::post('season_goals',[ApiAthleteController::class,'season_goals']);
Route::post('dreams_goals',[ApiAthleteController::class,'dreams_goals']);
Route::post('wellbeing',[ApiAthleteController::class,'wellbeing']);
Route::post('start_goals',[ApiAthleteController::class,'start_goals']);
Route::post('self_talks',[ApiAthleteController::class,'self_talks']);
Route::post('post_performance',[ApiAthleteController::class,'post_performance']);
Route::post('delete_performance',[ApiAthleteController::class,'delete_performance']);
Route::post('post_improvement',[ApiAthleteController::class,'post_improvement']);
Route::post('delete_improvement',[ApiAthleteController::class,'delete_improvement']);

Route::post('view_sports',[ApiAthleteController::class,'view_sports']);
Route::post('visualization',[ApiAthleteController::class,'visualization']);
Route::post('view_visualization',[ApiAthleteController::class,'view_visualization']);
Route::post('delete_visualization',[ApiAthleteController::class,'delete_visualization']);
Route::post('start_selftalk',[ApiAthleteController::class,'start_selftalk']);
Route::post('view_start_selftalk',[ApiAthleteController::class,'view_start_selftalk']);
Route::post('delete_start_selftalk',[ApiAthleteController::class,'delete_start_selftalk']);
Route::post('subscription_plan',[ApiAthleteController::class,'subscription_plan']);
Route::post('delete_notification',[ApiAthleteController::class,'delete_notification']);
Route::post('view_improvements',[ApiAthleteController::class,'view_improvements']);
Route::post('view_performances',[ApiAthleteController::class,'view_performances']);
Route::post('support_count',[ApiAthleteController::class,'support_count']);
Route::get('view_therapist_support',[ApiTherapistController::class,'view_support']);
Route::post('therapist_profile',[ApiTherapistController::class,'profile']);
Route::post('therapist_for_review',[ApiAthleteController::class,'therapist_for_review']);
Route::post('therapist_appointment_slot',[ApiTherapistController::class,'therapist_appointment_slot']);  
Route::post('booking',[ApiTherapistController::class,'booking']);  

Route::post('therapist_support_video',[ApiTherapistController::class,'therapist_support_video']);  


});




