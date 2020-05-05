<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('employers/messenger-type', 'API\EmployerController@messengerType');
    Route::get('employers/industries', 'API\EmployerController@industries');
    Route::get('employers/statuses', 'API\EmployerController@statuses');
    Route::get('employers/requisitions', 'API\EmployerController@requisitions');
    Route::get('employers/{employer}/users', 'API\EmployerController@getUsers');
    Route::post('employers/{employerId}/comment', 'API\EmployerController@commentUpdate');
    Route::post('employers/{employerId}/assign', 'API\EmployerController@updateAssign');
    Route::get('countries/cities/{stateId}', 'API\CountryController@getCity');
    Route::get('countries/states/{countryId}', 'API\CountryController@getState');
    Route::get('countries', 'API\CountryController@getCountry');
    Route::get('users/notifications/mark-as-read', 'API\UserController@markReadNotification');
    Route::get('users/notifications', 'API\UserController@notifications');
    Route::apiResources([
		'users' => 'API\UserController',
		'employers' => 'API\EmployerController'
	]);
});
