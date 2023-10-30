<?php

/*
 * API url will be: <base-url>/public/api/basic/blogs
 */
Route::group(
    [
        'prefix' => 'basic/blogs',
        'namespace' => 'Backend',
    ],
    function () {

        /**
         * Get Assets
         */
        Route::get('/assets', 'BlogsController@getAssets')
            ->name('vh.backend.basic.api.blogs.assets');
        /**
         * Get List
         */
        Route::get('/', 'BlogsController@getList')
            ->name('vh.backend.basic.api.blogs.list');
        /**
         * Update List
         */
        Route::match(['put', 'patch'], '/', 'BlogsController@updateList')
            ->name('vh.backend.basic.api.blogs.list.update');
        /**
         * Delete List
         */
        Route::delete('/', 'BlogsController@deleteList')
            ->name('vh.backend.basic.api.blogs.list.delete');


        /**
         * Create Item
         */
        Route::post('/', 'BlogsController@createItem')
            ->name('vh.backend.basic.api.blogs.create');
        /**
         * Get Item
         */
        Route::get('/{id}', 'BlogsController@getItem')
            ->name('vh.backend.basic.api.blogs.read');
        /**
         * Update Item
         */
        Route::match(['put', 'patch'], '/{id}', 'BlogsController@updateItem')
            ->name('vh.backend.basic.api.blogs.update');
        /**
         * Delete Item
         */
        Route::delete('/{id}', 'BlogsController@deleteItem')
            ->name('vh.backend.basic.api.blogs.delete');

        /**
         * List Actions
         */
        Route::any('/action/{action}', 'BlogsController@listAction')
            ->name('vh.backend.basic.api.blogs.list.action');

        /**
         * Item actions
         */
        Route::any('/{id}/action/{action}', 'BlogsController@itemAction')
            ->name('vh.backend.basic.api.blogs.item.action');



    });
