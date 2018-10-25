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

namespace HoneyComb\Menu\Http\Controllers\Admin;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Menu\Enum\HCMenuTypeEnum;
use HoneyComb\Menu\Events\Admin\Menu\HCMenuCreated;
use HoneyComb\Menu\Events\Admin\Menu\HCMenuForceDeleted;
use HoneyComb\Menu\Events\Admin\Menu\HCMenuRestored;
use HoneyComb\Menu\Events\Admin\Menu\HCMenuSoftDeleted;
use HoneyComb\Menu\Events\Admin\Menu\HCMenuUpdated;
use HoneyComb\Menu\Models\HCMenu;
use HoneyComb\Menu\Requests\Admin\HCMenuRequest;
use HoneyComb\Menu\Requests\Admin\HCMenuTypeRequest;
use HoneyComb\Menu\Services\HCMenuService;
use HoneyComb\Menu\Services\HCMenuTypeService;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class HCMenuController
 * @package HoneyComb\Menu\Http\Controllers\Admin
 */
class HCMenuController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCMenuService
     */
    protected $service;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * @var HCFrontendResponse
     */
    private $menuTypeService;

    /**
     * HCMenuController constructor.
     * @param \Illuminate\Database\Connection $connection
     * @param \HoneyComb\Starter\Helpers\HCFrontendResponse $response
     * @param \HoneyComb\Menu\Services\HCMenuService $service
     * @param \HoneyComb\Menu\Services\HCMenuTypeService $menuTypeService
     */
    public function __construct(
        Connection $connection,
        HCFrontendResponse $response,
        HCMenuService $service,
        HCMenuTypeService $menuTypeService
    ) {
        $this->connection = $connection;
        $this->response = $response;
        $this->service = $service;
        $this->menuTypeService = $menuTypeService;
    }

    /**
     * Admin panel page view
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('HCMenu::menu.page_title'),
            'url' => route('admin.api.menu'),
            'form' => route('admin.api.form-manager', ['menu']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_menu_menu',
                ['_delete', '_force_delete', '_merge', '_restore', '_clone']),
            'filters' => $this->getFilters(),
            'type' => 'sortable-tree',
            'disablePagination' => true,
        ];

        return view('HCCore::admin.service.index', ['config' => $config]);
    }

    /**
     * Get admin page table columns settings
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        $columns = [
            'language_code' => $this->headerText(trans('HCMenu::menu.language_code')),
            'type_id' => $this->headerText(trans('HCMenu::menu.type_id')),
            'parent_id' => $this->headerText(trans('HCMenu::menu.parent_id')),
            'label' => $this->headerText(trans('HCMenu::menu.label')),
            'target' => $this->headerText(trans('HCMenu::menu.target')),
            'sequence' => $this->headerText(trans('HCMenu::menu.sequence')),
            'icon' => $this->headerText(trans('HCMenu::menu.icon')),
            'url' => $this->headerText(trans('HCMenu::menu.url')),
            'url_text' => $this->headerText(trans('HCMenu::menu.url_text')),
            'url_target' => $this->headerText(trans('HCMenu::menu.url_target')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return \HoneyComb\Menu\Models\HCMenu|\HoneyComb\Menu\Repositories\HCMenuRepository|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById(string $id)
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list for menu component which means no Paginate. All records with children
     * @param HCMenuRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCMenuRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListWithChildren($request)
        );
    }

    /**
     * Create data list
     * @param HCMenuRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCMenuRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getOptions($request)
        );
    }

    /**
     * Creating record
     *
     * @param HCMenuRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(HCMenuRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            /** @var HCMenu $record */
            $record = $this->service->getRepository()->create($request->getRecordData());

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();

            return $this->response->error($e->getMessage());
        }

        event(new HCMenuCreated($record));

        return $this->response->success("Created");
    }

    /**
     * Updating menu group record
     *
     * @param HCMenuRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCMenuRequest $request, string $id): JsonResponse
    {
        /** @var HCMenu $record */
        $record = $this->service->getRepository()->findOneBy(['id' => $id]);
        $record->update($request->getRecordData());

        if ($record) {
            $record = $this->service->getRepository()->find($id);

            event(new HCMenuUpdated($record));
        }

        return $this->response->success("Created");
    }

    /**
     * @param HCMenuRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteSoft(HCMenuRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $deleted = $this->service->getRepository()->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        event(new HCMenuSoftDeleted($deleted));

        return $this->response->success('Successfully deleted');
    }

    /**
     * @param HCMenuRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function restore(HCMenuRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $restored = $this->service->getRepository()->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        event(new HCMenuRestored($restored));

        return $this->response->success('Successfully restored');
    }

    /**
     * @param HCMenuRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteForce(HCMenuRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $deleted = $this->service->getRepository()->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        event(new HCMenuForceDeleted($deleted));

        return $this->response->success('Successfully deleted');
    }

    /**
     * Getting filters
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getFilters()
    {
        return [
            'type_id' => [
                'type' => 'dropDownList',
                'required' => 1,
                'options' => $this->menuTypeService->getRepository()->getOptions(new HCMenuTypeRequest()),
            ],
        ];
    }

    /**
     * @param \HoneyComb\Menu\Requests\Admin\HCMenuRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(HCMenuRequest $request)
    {
        foreach ($request->get('children') as $key => $value) {
            $this->service->getRepository()->update(['parent_id' => $request->get('parent'), 'sequence' => $key],
                ['id' => $value]);
        }

        //not sure if this is a good practice
        event(new HCMenuUpdated(new HCMenu()));

        return $this->response->success('Successfully updated');
    }
}