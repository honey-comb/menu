<?php
/**
 * @copyright 2018 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

Route::prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function() {

        Route::get('menu', 'HCMenuController@index')
            ->name('admin.menu.index')
            ->middleware('acl:honey_comb_menu_menu_admin_list');

        Route::prefix('api/menu')->group(function() {

            Route::get('/', 'HCMenuController@getListPaginate')
                ->name('admin.api.menu')
                ->middleware('acl:honey_comb_menu_menu_admin_list');

            Route::get('options', 'HCMenuController@getOptions')
                ->name('admin.api.menu.options');

            Route::post('/', 'HCMenuController@store')
                ->name('admin.api.menu.create')
                ->middleware('acl:honey_comb_menu_menu_admin_create');

            Route::delete('/', 'HCMenuController@deleteSoft')
                ->name('admin.api.menu.delete')
                ->middleware('acl:honey_comb_menu_menu_admin_delete');

            Route::delete('force', 'HCMenuController@deleteForce')
                ->name('admin.api.menu.delete.force')
                ->middleware('acl:honey_comb_menu_menu_admin_delete_force');

            Route::post('restore', 'HCMenuController@restore')
                ->name('admin.api.menu.restore')
                ->middleware('acl:honey_comb_menu_menu_admin_restore');

            Route::prefix('{id}')->group(function() {

                Route::get('/', 'HCMenuController@getById')
                    ->name('admin.api.menu.single')
                    ->middleware('acl:honey_comb_menu_menu_admin_list');

                Route::put('/', 'HCMenuController@update')
                    ->name('admin.api.menu.update')
                    ->middleware('acl:honey_comb_menu_menu_admin_update');

            });
        });

    });
