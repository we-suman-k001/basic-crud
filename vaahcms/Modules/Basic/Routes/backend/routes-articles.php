<?php

Route::group(
    [
        'prefix' => 'backend/basic/articles',
        
        'middleware' => ['web', 'has.backend.access'],
        
        'namespace' => 'Backend',
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', 'ArticlesController@getAssets')
        ->name('vh.backend.basic.articles.assets');
    /**
     * Get List
     */
    Route::get('/', 'ArticlesController@getList')
        ->name('vh.backend.basic.articles.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', 'ArticlesController@updateList')
        ->name('vh.backend.basic.articles.list.update');
    /**
     * Delete List
     */
    Route::delete('/', 'ArticlesController@deleteList')
        ->name('vh.backend.basic.articles.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', 'ArticlesController@fillItem')
        ->name('vh.backend.basic.articles.fill');

    /**
     * Create Item
     */
    Route::post('/', 'ArticlesController@createItem')
        ->name('vh.backend.basic.articles.create');
    /**
     * Get Item
     */
    Route::get('/{id}', 'ArticlesController@getItem')
        ->name('vh.backend.basic.articles.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', 'ArticlesController@updateItem')
        ->name('vh.backend.basic.articles.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', 'ArticlesController@deleteItem')
        ->name('vh.backend.basic.articles.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', 'ArticlesController@listAction')
        ->name('vh.backend.basic.articles.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', 'ArticlesController@itemAction')
        ->name('vh.backend.basic.articles.item.action');

    //---------------------------------------------------------

});
