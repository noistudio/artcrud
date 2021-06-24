<?php

$prefix=config("artcrud.url_prefix");

Route::prefix($prefix)->middleware(config("artcrud.middlewares"))->group(function () {
    Route::get("/table_crud/create", [\Artcrud\controllers\CrudController::class, "create"])->name("artcrud.create");

    Route::post("/table_crud/docreate", [\Artcrud\controllers\CrudController::class, "docreate"])->name("artcrud.docreate");

    Route::post("/table_crud/get_field", [\Artcrud\controllers\CrudController::class, "get_field"])->name("artcrud.get_field");

});


