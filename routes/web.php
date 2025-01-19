<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return view('pets');
});


Route::get('/pet/{id}', [PetController::class, 'getPet']);
