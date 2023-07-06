<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TherepistController;
use App\Http\Controllers\ZoomController;


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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('index',[AdminController::class,'index']);
Route::get('privacy_policy',[AdminController::class,'privacy_policy']);
Route::get('stripe/{user_id}/{sub_id}/{amount}',[AdminController::class,'stripe']);

Route::get('appointment_payment/{user_id}/{sub_id}/{amount}/{start_time?}/{end_time?}/{date?}/{therapist_id?}/{appointment_id?}/',[AdminController::class,'appointment_payment']);

Route::post('appointPayment', [AdminController::class, 'appointPayment'])->name('appointPayment');
Route::post('stripe', [AdminController::class, 'stripePost'])->name('stripe.post');

Route::get('success',[AdminController::class,'success']);
Route::get('guide',[AdminController::class,'guide']);

Route::get('chat/{sender_id}/{receiver_id}/{type}',[AdminController::class,'chat'])->name('chat');
Route::post('sendmessage', [AdminController::class, 'sendMessage'])->name('sendMessage');
Route::post('ajax-request', [AdminController::class, 'handleRequest'])->name('handleRequest');


Route::post('typingNotification', [AdminController::class, 'typingNotification'])->name('typingNotification');
Route::get('/forgot-password', [AdminController::class,'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AdminController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AdminController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [AdminController::class, 'submitResetPasswordForm'])->name('reset.password.post');



Route::get('authorize', [ZoomController::class, 'zoomAuthorize']);
Route::get('callback', [ZoomController::class, 'callback'])->name('callback');
Route::get('event-calender', [ZoomController::class, 'eventCalender'])->name('eventCalender');
Route::get('getEvent', [ZoomController::class, 'getEvent'])->name('getEvent');
Route::post('/store-token', [ZoomController::class, 'storeToken'])->name('storeToken');




Route::group(['prefix' => 'Admin'], function () {

Route::get('login',[AdminController::class,'login_view'])->name('admin_login');
Route::post('login',[AdminController::class,'Admin_login']);
   
});
Route::group(['prefix' => 'Admin','middleware'=> ['auth:web','prevent-back-history']], function () {

Route::get('dashboard',[AdminController::class,'dashboard']);
Route::get('logout',[AdminController::class,'logout']);
Route::get('view_athletes',[AdminController::class,'view_athletes']);
Route::get('change_athlete_status',[AdminController::class,'change_athlete_status']);
Route::get('notification',[AdminController::class,'notification']);
Route::post('save_notification',[AdminController::class,'save_notification']);
Route::get('view_notification',[AdminController::class,'view_notification']);
Route::get('questions',[AdminController::class,'questions']);
Route::post('save_questions',[AdminController::class,'save_questions']);
Route::get('view_questions',[AdminController::class,'view_questions']);
Route::get('privacy',[AdminController::class,'privacy']);
Route::post('save_privacy',[AdminController::class,'save_privacy']);
Route::get('support',[AdminController::class,'support']);
Route::post('save_support',[AdminController::class,'save_support']);
Route::get('view_support',[AdminController::class,'view_support']);
Route::get('editquestion/{id}',[AdminController::class,'editquestion']);
Route::post('updatequestion/{id}',[AdminController::class,'updatequestion']);
Route::get('deletequestion/{id}',[AdminController::class,'deletequestion']);
Route::get('deletenotification/{id}',[AdminController::class,'deletenotification']);
Route::get('deletesupport/{id}',[AdminController::class,'deletesupport']);
Route::get('editsupport/{id}',[AdminController::class,'editsupport']);
Route::post('updatesupport/{id}',[AdminController::class,'updatesupport']);
Route::get('addplan',[AdminController::class,'addplan']);
Route::post('saveplan',[AdminController::class,'saveplan']);
Route::get('viewplan',[AdminController::class,'viewplan']);
Route::get('editplan/{id}',[AdminController::class,'editplan']);
Route::post('updateplan/{id}',[AdminController::class,'updateplan']);
Route::get('deleteplan/{id}',[AdminController::class,'deleteplan']);
Route::get('sports',[AdminController::class,'sports']);
Route::post('savesports',[AdminController::class,'savesports']);
Route::get('viewsports',[AdminController::class,'viewsports']);
Route::get('editsports/{id}',[AdminController::class,'editsports']);
Route::post('updatesports/{id}',[AdminController::class,'updatesports']);
Route::get('deletesports/{id}',[AdminController::class,'deletesports']);
Route::get('points',[AdminController::class,'points']);
Route::post('savepoints',[AdminController::class,'savepoints']);
Route::get('athletedetails/{id}',[AdminController::class,'athletedetails']);
Route::get('subscription',[AdminController::class,'subscription']);
Route::post('savesubscription',[AdminController::class,'savesubscription']);
Route::get('viewsubscription',[AdminController::class,'viewsubscription']);
Route::get('editsubscription/{id}',[AdminController::class,'editsubscription']);
Route::post('updatesubscription/{id}',[AdminController::class,'updatesubscription']);
Route::get('deletesubscription/{id}',[AdminController::class,'deletesubscription']);
// therapist admin side
Route::get('view_therepist',[AdminController::class,'view_therapist'])->name('view_therapist');
Route::get('deletetherepist/{id}',[AdminController::class,'deletetherepist'])->name('deletetherepist');
Route::get('change_therepist_status',[AdminController::class,'change_therepist_status'])->name('change_therepist_status');
Route::get('add_therepist',[AdminController::class,'addtherepist'])->name('addtherepist');
Route::post('fetch-states', [AdminController::class, 'fetchState'])->name('fetchState');
Route::post('save_therepist',[AdminController::class,'savetherepist'])->name('savetherepist');
Route::get('edit_therepist/{id}',[AdminController::class,'edit_therepist'])->name('edit_therepist');
Route::post('update_therepist',[AdminController::class,'update_therepist'])->name('update_therepist');
Route::get('subscription_payment',[AdminController::class,'subscription_payment'])->name('subscription_payment');
Route::get('appoitment-Payment',[AdminController::class,'appoiPayment'])->name('appoiPayment');
Route::any('admin-change-password',[AdminController::class,'AdminchangePassword'])->name('AdminchangePassword');
// end therapist admin side

});

///  Therepist admin Penel

Route::group(['prefix' => 'Therapist'], function () {

    Route::get('login',[TherepistController::class,'therepist_login_view'])->name('therapist_login_view');
    Route::post('therapist-login',[TherepistController::class,'therepist_login'])->name('therepist_login');
       
});
Route::group(['prefix' => 'Therapist','middleware'=> ['auth:therapist']], function () {
   
    Route::get('dashboard',[TherepistController::class,'dashboard'])->name('therepist_dashboard');
    Route::get('logout',[TherepistController::class,'logout'])->name('therapist_logout');
    Route::get('profile',[TherepistController::class,'profile'])->name('profile');
    Route::post('update_therepist',[TherepistController::class,'update_therepist'])->name('update_therepist_profile');
    Route::get('support',[TherepistController::class,'support'])->name('add_support');
    Route::post('save_support',[TherepistController::class,'save_support']);
    Route::get('view_support',[TherepistController::class,'view_support'])->name('view_support_therapist');
    Route::get('deletesupport/{id}',[TherepistController::class,'deletesupport']);
    Route::get('editsupport/{id}',[TherepistController::class,'editsupport']);
    Route::post('updatesupport/{id}',[TherepistController::class,'updatesupport']);
    Route::get('view_notification',[TherepistController::class,'view_notification']);
    Route::get('view_feedback',[TherepistController::class,'view_feedback'])->name('view_feedback');

    Route::get('appointment-time',[TherepistController::class,'appointmentTime'])->name('appointmentTime');
    Route::get('add-appointment-time',[TherepistController::class,'addAppointmentTime'])->name('addAppointmentTime');
    Route::post('save-appointment-time',[TherepistController::class,'saveAppointmentTime'])->name('saveAppointmentTime'); 
    Route::get('edit-appointment-time/{id}',[TherepistController::class,'editAppointmentTime'])->name('editAppointmentTime'); 
    Route::post('update-appointment-time',[TherepistController::class,'updateAppointmentTime'])->name('updateAppointmentTime');         
    Route::get('deleteAppointmentTime/{id}',[TherepistController::class,'deleteAppointmentTime']);
    Route::get('clear-appointment-time/{id}',[TherepistController::class,'clearAppointmentTime'])->name('clearAppointmentTime'); 
    Route::get('chat-listing',[TherepistController::class,'chatListing'])->name('chatListing'); 
    Route::any('change-password',[TherepistController::class,'changePassword'])->name('changePassword');
});
