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

        Route::get('menu/types', 'HCMenuTypeController@index')
            ->name('admin.menu.types.index')
            ->middleware('acl:honey_comb_menu_menu_types_list');

        Route::prefix('api/menu/types')->group(function() {

            Route::get('options', 'HCMenuTypeController@getOptions')
                ->name('admin.api.menu.types.options');

            Route::get('/', 'HCMenuTypeController@getListPaginate')
                ->name('admin.api.menu.types')
                ->middleware('acl:honey_comb_menu_menu_types_list');

            Route::prefix('{id}')->group(function() {

                Route::get('/', 'HCMenuTypeController@getById')
                    ->name('admin.api.menu.types.single')
                    ->middleware('acl:honey_comb_menu_menu_types_list');

                Route::put('/', 'HCMenuTypeController@update')
                    ->name('admin.api.menu.types.update')
                    ->middleware('acl:honey_comb_menu_menu_types_update');

            });
        });
    });
