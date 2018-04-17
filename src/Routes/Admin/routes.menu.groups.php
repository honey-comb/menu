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

Route::domain(config('hc.admin_domain'))
    ->prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function() {

        Route::get('menu/group', 'HCMenuGroupController@index')
            ->name('admin.menu.group.index')
            ->middleware('acl:honey_comb_menu_menu_group_admin_list');

        Route::prefix('api/menu/group')->group(function() {

            Route::get('/', 'HCMenuGroupController@getListPaginate')
                ->name('admin.api.menu.group')
                ->middleware('acl:honey_comb_menu_menu_group_admin_list');

            Route::get('options', 'HCMenuGroupController@getOptions')
                ->name('admin.api.menu.group.options');

            Route::post('/', 'HCMenuGroupController@store')
                ->name('admin.api.menu.group.create')
                ->middleware('acl:honey_comb_menu_menu_group_admin_create');

            Route::delete('/', 'HCMenuGroupController@deleteSoft')
                ->name('admin.api.menu.group.delete')
                ->middleware('acl:honey_comb_menu_menu_group_admin_delete');

            Route::delete('force', 'HCMenuGroupController@deleteForce')
                ->name('admin.api.menu.group.delete.force')
                ->middleware('acl:honey_comb_menu_menu_group_admin_delete_force');

            Route::post('restore', 'HCMenuGroupController@restore')
                ->name('admin.api.menu.group.restore')
                ->middleware('acl:honey_comb_menu_menu_group_admin_restore');


            Route::prefix('{id}')->group(function() {

                Route::get('/', 'HCMenuGroupController@getById')
                    ->name('admin.api.menu.group.single')
                    ->middleware('acl:honey_comb_menu_menu_group_admin_list');

                Route::put('/', 'HCMenuGroupController@update')
                    ->name('admin.api.menu.group.update')
                    ->middleware('acl:honey_comb_menu_menu_group_admin_update');

            });
        });

    });
