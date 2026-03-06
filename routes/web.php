<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

//testing service container

Route::get('/test', function () {
    return app('testService');
});

