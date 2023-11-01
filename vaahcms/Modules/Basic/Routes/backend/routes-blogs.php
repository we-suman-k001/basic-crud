<?php


use VaahCms\Modules\Basic\Http\Controllers\Backend\BlogsController;

Route::group(
    [
        'prefix' => 'backend/basic/blogs',

        'middleware' => ['web', 'has.backend.access'],

        'namespace' => 'Backend',
    ],
    function () {
        Route::get('/assets',[BlogsController::class,'getAssets'])
            ->name('vh.backend.basic.blogs.assets');
        Route::get('/',[BlogsController::class,'getList'])
            ->name('vh.backend.basic.blogs.list');
        Route::match(['put', 'patch'], '/', [BlogsController::class,'updateList'])
            ->name('vh.backend.basic.blogs.list.update');
        Route::delete('/', [BlogsController::class,'deleteList'])
            ->name('vh.backend.basic.blogs.list.delete');
        Route::any('/fill', [BlogsController::class,'fillItem'])
            ->name('vh.backend.basic.blogs.fill');
        Route::post('/', [BlogsController::class,'createItem'])
            ->name('vh.backend.basic.blogs.create');
        Route::get( '/seed',[BlogsController::class,'seeder']);
        Route::get('/{id}', [BlogsController::class,'getItem'])
            ->name('vh.backend.basic.blogs.read');
        Route::match(['put', 'patch'], '/{id}', [BlogsController::class,'updateItem'])
            ->name('vh.backend.basic.blogs.update');
        Route::delete('/{id}', [BlogsController::class,'deleteItem'])
            ->name('vh.backend.basic.blogs.delete');
        Route::any('/action/{action}', [BlogsController::class,'listAction'])
            ->name('vh.backend.basic.blogs.list.actions');
        Route::any('/{id}/action/{action}', [BlogsController::class,'itemAction'])
            ->name('vh.backend.basic.blogs.item.action');
        //---------------------------------------------------------

    });

