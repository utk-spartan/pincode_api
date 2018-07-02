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

Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});


Route::get('/info', 'PincodeController@searchAddress');
Route::get('/{pin}', 'PincodeController@searchPin')->where('pin', '[0-9]+');
Route::get('/{_}',function(){return response()->json("Invalid pincode",404);});
Route::get('/',function(){return response()->json("Invalid pincode",404);});