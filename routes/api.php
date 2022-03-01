<?php

Route::group(['prefix' => 'fields/tags'], function () {
    Route::get('', 'FieldController@index');
});
