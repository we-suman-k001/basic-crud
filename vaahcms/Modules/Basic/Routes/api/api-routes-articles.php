<?php

/*
 * API url will be: <base-url>/public/api/basic/articles
 */
Route::group(
    [
        'prefix' => 'basic/articles',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', 'ArticlesController@getAssets')
        ->name('vh.backend.basic.api.articles.assets');
    /**
     * Get List
     */
    Route::get('/', 'ArticlesController@getList')
        ->name('vh.backend.basic.api.articles.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'ArticlesController@updateList')
        ->name('vh.backend.basic.api.articles.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ArticlesController@deleteList')
        ->name('vh.backend.basic.api.articles.list.delete');


    /**
     * Create Item
     */
    Route::post('/', 'ArticlesController@createItem')
        ->name('vh.backend.basic.api.articles.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ArticlesController@getItem')
        ->name('vh.backend.basic.api.articles.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ArticlesController@updateItem')
        ->name('vh.backend.basic.api.articles.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ArticlesController@deleteItem')
        ->name('vh.backend.basic.api.articles.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ArticlesController@listAction')
        ->name('vh.backend.basic.api.articles.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ArticlesController@itemAction')
        ->name('vh.backend.basic.api.articles.item.action');



});
