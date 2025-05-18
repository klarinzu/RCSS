<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SummerNoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HolidayController;

use Illuminate\Http\Request;


Auth::routes();

Route::get('/',[FrontendController::class,'index'])->name('home');

Route::middleware(['auth'])->group(function () {

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::resource('users', UserController::class)->middleware('permission:users.view|users.create|users.edit|users.delete');
        Route::get('users-trash', [UserController::class, 'trashView'])->name('users.trash');
        Route::get('users-restore/{id}', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users-delete/{id}', [UserController::class, 'force_delete'])->name('users.force.delete');

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('permission:settings.view');
        Route::post('settings/{setting}', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings.update');

        // Categories
        Route::resource('categories', CategoryController::class)->middleware('permission:categories.view|categories.create|categories.edit|categories.delete');

        // Services
        Route::resource('services', ServiceController::class)->middleware('permission:services.view|services.create|services.edit|services.delete');
        Route::get('services-trash', [ServiceController::class, 'trashView'])->name('services.trash');
        Route::get('services-restore/{id}', [ServiceController::class, 'restore'])->name('services.restore');
        Route::delete('services-delete/{id}', [ServiceController::class, 'force_delete'])->name('services.force.delete');

        // Employees
        Route::resource('employees', EmployeeController::class)->middleware('permission:employees.view|employees.create|employees.edit|employees.delete');
        Route::get('employee-bookings', [UserController::class, 'EmployeeBookings'])->name('employees.bookings');
        Route::get('employee-booking/{id}', [UserController::class, 'show'])->name('employees.booking.detail');

        // Holidays
        Route::resource('holidays', HolidayController::class)->middleware('permission:holidays.view|holidays.create|holidays.edit|holidays.delete');

        // Summernote
        Route::post('summernote', [SummerNoteController::class, 'summerUpload'])->name('summernote.upload')->middleware('permission:settings.update');
        Route::post('summernote/delete', [SummerNoteController::class, 'summerDelete'])->name('summernote.delete')->middleware('permission:settings.update');
    });

    // Profile routes
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::patch('profile-update/{user}', [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::patch('profile-password/{user}', [ProfileController::class, 'passwordUpdate'])->name('profile.password');
    Route::put('profile-pic/{user}', [ProfileController::class, 'updateProfileImage'])->name('profile.image.update');
    Route::patch('delete-profile-image/{user}', [ProfileController::class, 'deleteProfileImage'])->name('profile.image.delete');

    // Employee profile routes
    Route::patch('employee-profile-update/{employee}', [ProfileController::class, 'employeeProfileUpdate'])->name('employee.profile.update');
    Route::put('employee-bio/{employee}', [EmployeeController::class, 'updateBio'])->name('employee.bio.update');

    // Appointments
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index')->middleware('permission:appointments.view| appointments.create | services.appointments | appointments.delete');
    Route::post('appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update.status');
    Route::post('update-status', [DashboardController::class, 'updateStatus'])->name('dashboard.update.status');

    Route::get('test',function(Request $request){
        return view('test',  [
            'request' => $request
        ]);
    });



    Route::post('test', function (Request $request) {
        dd($request->all())->toArray();
    })->name('test');

});



//frontend routes
Route::get('/services', [FrontendController::class, 'services'])->name('services');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

//fetch services from categories
Route::get('/categories/{category}/services', [FrontendController::class, 'getServices'])->name('get.services');

//fetch employee from category
Route::get('/services/{service}/employees', [FrontendController::class, 'getEmployees'])->name('get.employees');

//get availibility
Route::get('/employees/{employee}/availability/{date?}', [FrontendController::class, 'getEmployeeAvailability'])
    ->name('employee.availability');

//create appointment
Route::post('/bookings', [AppointmentController::class, 'store'])->name('bookings.store');
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments')->middleware('permission:appointments.view| appointments.create | services.appointments | appointments.delete');

Route::post('/appointments/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update.status');

//update status from dashbaord
Route::post('/update-status', [DashboardController::class, 'updateStatus'])->name('dashboard.update.status');


