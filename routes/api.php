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
Route::post('/login','MobileApplicationController@login');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/getsaleamount','MobileApplicationController@getSaleAmount');

Route::middleware(['custom-middleware'])->group(function () {


    Route::post('/logout','MobileApplicationController@logout');
    
    Route::get('/get-permissions','MobileApplicationController@get_permissions');

    Route::get('/get-distributors','MobileApplicationController@get_distributor');

    Route::get('/get-brands','MobileApplicationController@get_brands');

    Route::get('/get-products','MobileApplicationController@get_products');
    
    Route::get('/get-stock','MobileApplicationController@get_stock');

    //apis for smr hrms api

    
    Route::get('/getAttendance','MobileApplicationController@getAttendance');
    Route::get('/viewAttendanceReport','MobileApplicationController@viewAttendanceReport');
    Route::post('/addAttendance','MobileApplicationController@addAttendance');

    //end smr hrms apis

    Route::post('/CreateSaleOrder','MobileApplicationController@CreateSaleOrder');


    Route::get('/SaleOrderList','MobileApplicationController@SaleOrderList');
    Route::get('/getSaleOrder', 'MobileApplicationController@getSaleOrder');
    Route::get('/getSalesData', 'MobileApplicationController@getSalesData');
    Route::post('/updateSaleOrder','MobileApplicationController@updateSaleOrder');

    Route::post('/CreateReturnSaleOrder','MobileApplicationController@CreateReturnSaleOrder');
    Route::get('/ReturnSaleOrderList','MobileApplicationController@ReturnSaleOrderList');
    Route::get('/getReturnSaleOrder', 'MobileApplicationController@getReturnSaleOrder');
    Route::post('/updateReturnSaleOrder','MobileApplicationController@updateReturnSaleOrder');


    
    Route::post('/merchandiseCreate','MobileApplicationController@merchandiseCreate');
    Route::get('/merchandiseList','MobileApplicationController@merchandiseList');

    Route::post('/createSurvey','MobileApplicationController@createSurvey');
    Route::get('/surveyList','MobileApplicationController@surveyList');
    Route::get('/surveyData','MobileApplicationController@surveyData');

    // Route::controller('SalesController')->prefix("survey")->group(function(){
    //     Route::post('createSurvey', "createSurvey");
    //     Route::get('surveyList', "surveyList");
    //     Route::get('surveyData', "surveyData");
        
    //   });
    

    Route::get('/protected-route', function () {
        return response()->json([
            'message' => 'Authorized',
            'user_id' => Auth::user()->id,
            'user' => Auth::user(),
        ]);
    });

});
