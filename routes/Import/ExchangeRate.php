<?php

Route::group(['prefix' => 'import/ExchangeRate'], function () {
    
    Route::get('/', 'Import\ExchangeRateController@index');
    Route::get('/create', 'Import\ExchangeRateController@create');
    Route::post('/store', 'Import\ExchangeRateController@store')->name('ExchangeRate.store');
    Route::get('/edit/{id}', 'Import\ExchangeRateController@edit')->name('ExchangeRate.edit');
    Route::post('/update/{id}', 'Import\ExchangeRateController@update')->name('ExchangeRate.update');
    
    Route::get('/delete/{id}', 'Import\ExchangeRateController@deleteExchangeRate')->name('ExchangeRate.delete');


    // Define the cancel route
    Route::get('/cancel', function () {
        return redirect(url('import/ExchangeRate/'));
        // Replace 'ExchangeRate.index' with your actual index route
    })->name('ExchangeRate.cancel');

});

