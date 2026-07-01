<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskDashboardController;

Route::get('/', [TaskDashboardController::class, 'index'])->name('home');
Route::post('/task/run', [TaskDashboardController::class, 'runCommand'])->name('task.run');
Route::post('/task/update-interval', [TaskDashboardController::class, 'updateInterval'])->name('task.updateInterval');
Route::get('/task/chart-data', [TaskDashboardController::class, 'getChartData'])->name('task.chartData');