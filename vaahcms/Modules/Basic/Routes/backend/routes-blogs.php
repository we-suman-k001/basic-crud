<?php


use VaahCms\Modules\Basic\Http\Controllers\Backend\BlogsController;

Route::group(
    [
        'prefix' => 'backend/basic/blogs',

        'middleware' => ['web', 'has.backend.access'],

        'namespace' => 'Backend',
    ],
    function () {
        /**
         * Get Assets
         */
        Route::get('/assets',[BlogsController::class,'getAssets'])
            ->name('vh.backend.basic.blogs.assets');
        /**
         * Get List
         */
        Route::get('/',[BlogsController::class,'getList'])
            ->name('vh.backend.basic.blogs.list');
        /**
         * Update List
         */
        Route::match(['put', 'patch'], '/', [BlogsController::class,'updateList'])
            ->name('vh.backend.basic.blogs.list.update');
        /**
         * Delete List
         */
        Route::delete('/', [BlogsController::class,'deleteList'])
            ->name('vh.backend.basic.blogs.list.delete');


        /**
         * Fill Form Inputs
         */
        Route::any('/fill', [BlogsController::class,'fillItem'])
            ->name('vh.backend.basic.blogs.fill');

        /**
         * Create Item
         */
        Route::post('/', [BlogsController::class,'createItem'])
            ->name('vh.backend.basic.blogs.create');
        /**
         * Get Item
         */
        Route::get('/{id}', [BlogsController::class,'getItem'])
            ->name('vh.backend.basic.blogs.read');
        /**
         * Update Item
         */
        Route::match(['put', 'patch'], '/{id}', [BlogsController::class,'updateItem'])
            ->name('vh.backend.basic.blogs.update');
        /**
         * Delete Item
         */
        Route::delete('/{id}', [BlogsController::class,'deleteItem'])
            ->name('vh.backend.basic.blogs.delete');

        /**
         * List Actions
         */
        Route::any('/action/{action}', [BlogsController::class,'listAction'])
            ->name('vh.backend.basic.blogs.list.actions');

        /**
         * Item actions
         */
        Route::any('/{id}/action/{action}', [BlogsController::class,'itemAction'])
            ->name('vh.backend.basic.blogs.item.action');
        //---------------------------------------------------------

    });

