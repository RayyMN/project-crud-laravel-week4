<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/student');
});

Route::get('/student', [StudentController::class, 'index'])->name('student.index');

Route::get('/student/add', [StudentController::class, 'create'])->name('student.create');
Route::post('/student/add', [StudentController::class, 'store'])->name('student.store');

Route::get('/student/{id}', [StudentController::class, 'show'])->name('student.show');

Route::get('/student/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
Route::put('/student/edit/{id}', [StudentController::class, 'update'])->name('student.update');

Route::delete('/student/delete/{id}', [StudentController::class, 'destroy'])->name('student.destroy');

