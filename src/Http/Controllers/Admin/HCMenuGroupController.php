<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Http\Controllers\Admin;

use HoneyComb\Menu\Requests\HCMenuGroupRequest;
use HoneyComb\Menu\Services\HCMenuGroupService;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HCMenuGroupController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCMenuGroupService
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
     * HCMenuGroupController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCMenuGroupService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCMenuGroupService $service)
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
            'title' => trans('HCMenu::menu_group.page_title'),
            'url' => route('admin.api.menu.group'),
            'form' => route('admin.api.form-manager', ['menu.group']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_menu_menu_group'),
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
            'id' => $this->headerText(trans('HCMenu::menu_group.id')),
        ];

        return $columns;
    }

    /**
     * Creating menu group record
     *
     * @param HCMenuGroupRequest $request
     * @return JsonResponse
     */
    public function store (HCMenuGroupRequest $request) : JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $model = $this->service->getRepository()->create();
            $model->updateTranslations($request->getTranslations());

            $this->connection->commit();
        } catch (\Exception $e)
        {
            $this->connection->rollBack();

            return $this->response->error($e->getMessage());
        }

        return $this->response->success("Created");
    }

    /**
     * Updating menu group record
     *
     * @param HCMenuGroupRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update (HCMenuGroupRequest $request, string $id) : JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->updateTranslations($request->getTranslations());

        return $this->response->success("Created");
    }

    /**
     * @param string $id
     * @return \HoneyComb\Menu\Repositories\HCMenuGroupRepository|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById (string $id)
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCMenuGroupRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCMenuGroupRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * @param HCMenuGroupRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteSoft(HCMenuGroupRequest $request): JsonResponse
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
     * @param HCMenuGroupRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function restore(HCMenuGroupRequest $request): JsonResponse
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
     * @param HCMenuGroupRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteForce(HCMenuGroupRequest $request): JsonResponse
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