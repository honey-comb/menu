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
use HoneyComb\Menu\Models\HCMenu;
use HoneyComb\Menu\Requests\Admin\HCMenuRequest;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Support\Collection;

/**
 * Class HCMenuRepository
 * @package HoneyComb\Menu\Repositories
 */
class HCMenuRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCMenu::class;
    }

    /**
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function deleteSoft(array $ids): array
    {
        $deleted = [];

        $records = $this->makeQuery()->whereIn('id', $ids)->get();

        /** @var HCMenu $record */
        foreach ($records as $record) {
            if($record->delete()) {
                $deleted[] = $record;            }
        }

        return $deleted;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function restore(array $ids): array
    {
        $restored = [];

        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        /** @var HCMenu $record */
        foreach ($records as $record) {
            if ($record->restore()) {
                $restored[] = $record;
            }
        }

        return $restored;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function deleteForce(array $ids): array
    {
        $deleted = [];

        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        /** @var HCMenu $record */
        foreach ($records as $record) {
            if($record->forceDelete()) {
                $deleted[] = $record;
            }
        }

        return $deleted;
    }

    /**
     * @param HCMenuRequest $request
     * @return \Illuminate\Support\Collection|static
     */
    public function getOptions(HCMenuRequest $request): Collection
    {
        return $this->createBuilderQuery($request)->get()->map(function ($record) {
            return [
                'id' => $record->id,
                'language_code' => $record->language_code
            ];
        });
    }
}