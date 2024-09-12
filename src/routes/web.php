<?php

use Br\ApiDocsPackage\ApiDocsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApiDocsController::class, 'index'])->name('api-docs.index');
Route::get('/{id}', [ApiDocsController::class, 'show'])->name('api-docs.show');