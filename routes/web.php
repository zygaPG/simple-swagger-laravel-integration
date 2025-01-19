<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;


Route::get('/', function () {
    return view('pets');
});


Route::get('/pets', [PetController::class, 'getPets']);
Route::post('/pet', [PetController::class, 'addPet']);
Route::put('/pet', [PetController::class, 'updatePet']);
Route::delete('/pet/{id}', [PetController::class, 'deletePet']);