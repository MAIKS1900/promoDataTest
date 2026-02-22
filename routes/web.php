<?php

use App\Http\Controllers\ReportProcessesController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/',  'reports');

Route::resource('reports', ReportProcessesController::class)
    ->only(['index']);