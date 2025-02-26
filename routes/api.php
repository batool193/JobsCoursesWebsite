<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\User\AuthUserController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\CompanySubscriptionController;
use App\Http\Controllers\Customer\AuthCustomerController;

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

Route::prefix('user')->group(function () {
    Route::post('login', [AuthUserController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', [AuthUserController::class, 'logout']);
        Route::post('refresh', [AuthUserController::class, 'refresh']);
        Route::get('{user}', [UserController::class, 'show']);
        Route::get('/', [UserController::class, 'index']);
        Route::put('{user}', [UserController::class, 'update']);
        Route::delete('{user}', [UserController::class, 'destroy']);

        Route::middleware(['role:admin'])->group(function () {
            Route::post('company', [CompanyController::class, 'store']);
            Route::post('/', [UserController::class, 'store']);
            Route::post('{user}/assign-role', [UserController::class, 'assignRoleToUser']);
        });
    });
});

Route::prefix('customer')->group(function () {
    Route::post('register', [AuthCustomerController::class, 'register']);
    Route::post('login', [AuthCustomerController::class, 'login']);


    Route::middleware(['auth:customer'])->group(function () {
        Route::post('logout', [AuthCustomerController::class, 'logout']);
        Route::post('refresh', [AuthCustomerController::class, 'refresh']);
        Route::put('{customer}', [CustomerController::class, 'update']);
        Route::delete('{customer}', [CustomerController::class, 'destroy']);
        Route::post('{customer}/addprofilephoto', [CustomerController::class, 'AddAttachement']);
        Route::post('{customer}/photo/{photo}/remove', [CustomerController::class, 'deleteAttachement']);
        Route::post('{customer}/applyjob', [CustomerController::class, 'AddAttachement']);
    });

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/', [CustomerController::class, 'store']);
});
});

Route::middleware(['auth:api'])->group(function () {
    Route::middleware(['role:company'])->group(function () {
        Route::put('company/{company}', [CompanyController::class, 'update']);
        Route::delete('company/{company}', [CompanyController::class, 'destroy']);
        Route::post('company/{company}/addlogo', [CompanyController::class, 'AddAttachement']);
        Route::post('company/{company}/logo/{logo}/remove', [CompanyController::class, 'deleteAttachement']);
        Route::post('course', [CourseController::class, 'store']);
        Route::put('course/{course}', [CourseController::class, 'update']);
        Route::delete('course/{course}', [CourseController::class, 'destroy']);
        Route::post('course/{course}/addvideo', [CourseController::class, 'AddAttachement']);
        Route::post('course/{course}/video/{video}/remove', [CourseController::class, 'deleteAttachement']);
        Route::post('job', [JobController::class, 'store']);
        Route::put('job/{job}', [JobController::class, 'update']);
        Route::delete('job/{job}', [JobController::class, 'destroy']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('subscriptions', [SubscriptionController::class, 'store']);
        Route::put('subscriptions/{subscription}', [SubscriptionController::class, 'update']);
        Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy']);
    });

    Route::prefix('companies')->middleware(['role:company'])->group(function () {
        Route::post('{company}/subscriptions/{subscription}', [CompanySubscriptionController::class, 'store']);
        Route::delete('{company}/subscriptions/{subscription}', [CompanySubscriptionController::class, 'destroy']);
    });
});

Route::get('customer', [CustomerController::class, 'index']);
Route::get('customer/{customer}', [CustomerController::class, 'show']);
Route::get('company', [CompanyController::class, 'index']);
Route::get('company/{company}', [CompanyController::class, 'show']);
Route::get('company/{company}/showjobs', [CompanyController::class, 'showjobs']);
Route::get('company/{company}/showcourses', [CompanyController::class, 'showcourses']);
Route::get('course', [CourseController::class, 'index']);
Route::get('course/{course}', [CourseController::class, 'show']);
Route::get('job', [JobController::class, 'index']);
Route::get('job/{job}', [JobController::class, 'show']);
Route::get('companies/{company}/subscriptions', [CompanySubscriptionController::class, 'show']);
Route::get('subscriptions', [SubscriptionController::class, 'index']);
Route::get('subscriptions/{subscription}', [SubscriptionController::class, 'show']);
