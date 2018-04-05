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

namespace HoneyComb\Menu\Repositories;

use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Menu\Models\HCMenuType;
use HoneyComb\Menu\Requests\Admin\HCMenuTypeRequest;
use HoneyComb\Starter\Repositories\HCBaseRepository;

/**
 * Class HCMenuTypeRepository
 * @package HoneyComb\Menu\Repositories
 */
class HCMenuTypeRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCMenuType::class;
    }

    /**
     * @param HCMenuTypeRequest $request
     * @return mixed
     */
    public function getOptions(HCMenuTypeRequest $request)
    {
        return $this->createBuilderQuery($request)->get()->map(function ($item) {
            $formatted = ['id' => $item->id];

            if ($item->translation)
                $formatted['label'] = $item->translation->label;

            return $formatted;
        });
    }

}