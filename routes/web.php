<?php

use App\Livewire\StoreMain;
use App\Livewire\ComboMain;
use Illuminate\Support\Facades\Route;

Route::get('/', function() {
    return redirect()->route('store');
});

Route::get('/tienda', StoreMain::class)->name('store');
Route::get('admin/combos', ComboMain::class)->name('admin.combos');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
