<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Http\Controllers\Admin;

use HoneyComb\Menu\Services\HCMenuTypeService;
use HoneyComb\Menu\Requests\HCMenuTypeRequest;
use HoneyComb\Menu\Models\HCMenuType;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HCMenuTypeController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCMenuTypeService
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
     * HCMenuTypeController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCMenuTypeService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCMenuTypeService $service)
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
            'title' => trans('HCMenu::menu_types.page_title'),
            'url' => route('admin.api.menu.types'),
            'form' => route('admin.api.form-manager', ['menu.types']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_menu_menu_types'),
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
            'id' => $this->headerText(trans('HCMenu::menu_types.id')),
            'translation.label' => $this->headerText(trans('HCMenu::menu_types.label')),
            'translation.description' => $this->headerText(trans('HCMenu::menu_types.description')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return HCMenuType|null
     */
    public function getById(string $id): ? HCMenuType
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCMenuTypeRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCMenuTypeRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Updating menu group record
     *
     * @param HCMenuTypeRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCMenuTypeRequest $request, string $id): JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());
        $model->updateTranslations($request->getTranslations());

        return $this->response->success("Created");
    }
}