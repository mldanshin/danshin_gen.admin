<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\Part\IndexController as PartIndexController;
use App\Http\Controllers\Part\PersonController as PartPersonController;
use App\Http\Controllers\Part\PeopleController as PartPeopleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.basic')->group(function() {
    Route::get("", IndexController::class)->name("index");
    Route::resource("person", PersonController::class)->except(["index", "show"]);
    Route::prefix("/part/")->name("part.")->group(function () {
        Route::get("", PartIndexController::class)->name("index");
        Route::get("people", [PartPeopleController::class, "show"])->name("people.show");
        Route::prefix("person/")->name("person.")->group(function () {
            Route::get("create", [PartPersonController::class, "create"])->name("create");
            Route::get("gender/create", [PartPersonController::class, "genderCreate"])->name("gender.create");
            Route::get("activity/create", [PartPersonController::class, "activityCreate"])->name("activity.create");
            Route::get("email/create", [PartPersonController::class, "emailCreate"])->name("email.create");
            Route::get("internet/create", [PartPersonController::class, "internetCreate"])->name("internet.create");
            Route::get("phone/create", [PartPersonController::class, "phoneCreate"])->name("phone.create");
            Route::get("residence/create", [PartPersonController::class, "residenceCreate"])->name("residence.create");
            Route::get("parent/create", [PartPersonController::class, "parentCreate"])->name("parent.create");
            Route::get("parent-aviable/create", [PartPersonController::class, "parentAviableCreate"])->name("parent_aviable.create");
            Route::get("{person}/edit", [PartPersonController::class, "edit"])->name("edit");
        });
    });
});
