<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can re[gister API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api', 'recurring-login']], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::get('settings/profile/{id}', 'Settings\ProfileController@retrieve');
        
    Route::get('notifications/{id}/{offset}/{limit}', 'SurveyController@retrieve_complete_user');
    Route::get('notifications/{id}', 'SurveyController@retrieve_complete_id');
    Route::get('survey_user/{id}/{offset}/{limit}', 'SurveyController@get_surveys_by_id');
    
    Route::get('user/details/{email}', 'AdminController@get_user_details');
    Route::post('user/delete/{id}', 'AdminController@delete_user');
    Route::post('user/clean/{id}', 'AdminController@clean_user_data');
    
    Route::post('pages/{id}', 'PageController@update_page');

    Route::post('survey', 'SurveyController@store');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

Route::get('survey/{id}', 'SurveyController@show');
Route::post('survey/completed', 'SurveyController@created_on');
Route::put('survey/notification/{id}', 'SurveyController@notification_sent');
Route::get('survey-complete/{id}', 'SurveyController@retrieve_complete');

Route::get('question/{id}', 'QuestionController@show');
Route::post('question', 'QuestionController@store');

Route::get('respondent/{id}', 'RespondentController@show');
Route::post('respondent', 'RespondentController@store');

Route::post('upload/audio', 'AnswerController@upload_audiofile');
Route::get('answer/{id}', 'AnswerController@show');
Route::post('answer', 'AnswerController@store');

Route::get('pages', 'PageController@get_all_pages');
Route::get('pages/{id}', 'PageController@get_page_data');

Route::delete('survey/{id}', 'SurveyController@delete_survey');

Route::post('survey-created', 'SendmailController@surveyCreated');
Route::post('survey-completed', 'SendmailController@surveyCompleted');
Route::post('handle-bounces', 'SendmailController@handleBounceOrComplaint');
Route::post('handle-complaints', 'SendmailController@handleBounceOrComplaint');
