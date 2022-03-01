<?php

Route::group(['prefix' => 'fields/tags'], function () {
    Route::post('', 'FieldController@store');
    Route::get('search', 'FieldController@search');
});
