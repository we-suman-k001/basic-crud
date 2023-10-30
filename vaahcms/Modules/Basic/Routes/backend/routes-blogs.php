<?php


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
        Route::get('/assets', 'BlogsController@getAssets')
            ->name('vh.backend.basic.blogs.assets');
        /**
         * Get List
         */
        Route::get('/', 'BlogsController@getList')
            ->name('vh.backend.basic.blogs.list');
        /**
         * Update List
         */
        Route::match(['put', 'patch'], '/', 'BlogsController@updateList')
            ->name('vh.backend.basic.blogs.list.update');
        /**
         * Delete List
         */
        Route::delete('/', 'BlogsController@deleteList')
            ->name('vh.backend.basic.blogs.list.delete');


        /**
         * Fill Form Inputs
         */
        Route::any('/fill', 'BlogsController@fillItem')
            ->name('vh.backend.basic.blogs.fill');

        /**
         * Create Item
         */
        Route::post('/', 'BlogsController@createItem')
            ->name('vh.backend.basic.blogs.create');
        /**
         * Get Item
         */
        Route::get('/{id}', 'BlogsController@getItem')
            ->name('vh.backend.basic.blogs.read');
        /**
         * Update Item
         */
        Route::match(['put', 'patch'], '/{id}', 'BlogsController@updateItem')
            ->name('vh.backend.basic.blogs.update');
        /**
         * Delete Item
         */
        Route::delete('/{id}', 'BlogsController@deleteItem')
            ->name('vh.backend.basic.blogs.delete');

        /**
         * List Actions
         */
        Route::any('/action/{action}', 'BlogsController@listAction')
            ->name('vh.backend.basic.blogs.list.actions');

        /**
         * Item actions
         */
        Route::any('/{id}/action/{action}', 'BlogsController@itemAction')
            ->name('vh.backend.basic.blogs.item.action');
        //---------------------------------------------------------

    });

