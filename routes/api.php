<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;

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

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::post('auth/register',[AuthController::class,'create']);
Route::post('auth/login',[AuthController::class,'login']);
//Proteccion de rutas
//Creando rutas de empleados y departamentos
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('departments',DepartmentController::class);
    Route::resource('employees',EmployeeController::class);
    Route::get('employeesbydepartment',[EmployeeController::class,'EmployeesByDepartment']);
    Route::get('employeesall',[EmployeeController::class,'all']);
    Route::get('auth/logout',[AuthController::class,'logout']);
});

