<?php

use Illuminate\Support\Facades\Route;

Route::get('bfg/doc', [\Bfg\OpenDoc\Controller::class, 'index'])
    ->name('bfg.documentation.index')
    ->middleware(config('open-doc.middleware', []));
