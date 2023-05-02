<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::post('/v1/register', [RegistrationController::class, 'postRegister'])->name('api.v1.register');

Route::get('posts', 'PostController@index');
Route::group(['prefix' => 'post'], function () {
    Route::post('add', 'PostController@add');
    Route::get('edit/{id}', 'PostController@edit');
    Route::post('update/{id}', 'PostController@update');
    Route::delete('delete/{id}', 'PostController@delete');
});