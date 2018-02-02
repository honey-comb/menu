<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Http\Controllers\Admin;

use HoneyComb\Menu\Services\HCMenuService;
use HoneyComb\Menu\Requests\HCMenuRequest;
use HoneyComb\Menu\Models\HCMenu;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
     * HCMenuController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCMenuService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCMenuService $service)
    {
        $this->connection = $connection;
        $this->response = $response;
        $this->service = $service;
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
            'actions' => $this->getActions('honey_comb_menu_menu'),
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
     * @return HCMenu|null
     */
    public function getById(string $id): ? HCMenu
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCMenuRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCMenuRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Creating record
     *
     * @param HCMenuRequest $request
     * @return JsonResponse
     */
    public function store(HCMenuRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $model = $this->service->getRepository()->create($request->getRecordData());

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();

            return $this->response->error($e->getMessage());
        }

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
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());

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
            $this->service->getRepository()->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

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
            $this->service->getRepository()->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

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
            $this->service->getRepository()->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Exception $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }
}