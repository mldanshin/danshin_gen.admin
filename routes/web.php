<?php

use App\Http\Controllers\FrontLogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Part\IndexController as PartIndexController;
use App\Http\Controllers\Part\PeopleController as PartPeopleController;
use App\Http\Controllers\Part\PersonController as PartPersonController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function () {
    Route::get('', IndexController::class)->name('index');
    Route::post('/log', FrontLogController::class)->name('log');
    Route::prefix('/part/')->name('part.')->group(function () {
        Route::get('', PartIndexController::class)->name('index');
        Route::get('people', [PartPeopleController::class, 'show'])->name('people.show');
        Route::prefix('person/')->name('person.')->group(function () {
            Route::get('create', [PartPersonController::class, 'create'])->name('create');
            Route::get('old-surname/create', [PartPersonController::class, 'oldSurnameCreate'])->name('old_surname.create');
            Route::get('activity/create', [PartPersonController::class, 'activityCreate'])->name('activity.create');
            Route::get('email/create', [PartPersonController::class, 'emailCreate'])->name('email.create');
            Route::get('internet/create', [PartPersonController::class, 'internetCreate'])->name('internet.create');
            Route::get('phone/create', [PartPersonController::class, 'phoneCreate'])->name('phone.create');
            Route::get('residence/create', [PartPersonController::class, 'residenceCreate'])->name('residence.create');
            Route::get('parent/create', [PartPersonController::class, 'parentCreate'])->name('parent.create');
            Route::get('parent-aviable/create', [PartPersonController::class, 'parentAviableCreate'])->name('parent_aviable.create');
            Route::get('marriage/create', [PartPersonController::class, 'marriageCreate'])->name('marriage.create');
            Route::get('marriage-aviable/create', [PartPersonController::class, 'marriageAviableCreate'])->name('marriage_aviable.create');
            Route::get('photo/create', [PartPersonController::class, 'photoCreate'])->name('photo.create');
            Route::get('photo/file/{personId}/{fileName}', [PartPersonController::class, 'photoFile'])->name('photo.file');
            Route::get('{person}/edit', [PartPersonController::class, 'edit'])->name('edit');
        });
    });
    Route::resource('person', PersonController::class)->except(['index', 'show']);
});
