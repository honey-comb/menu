<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Services;

use HoneyComb\Menu\Repositories\HCMenuTypeRepository;

class HCMenuTypeService
{
    /**
     * @var HCMenuTypeRepository
     */
    private $repository;

    /**
     * HCMenuTypeService constructor.
     * @param HCMenuTypeRepository $repository
     */
    public function __construct(HCMenuTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCMenuTypeRepository
     */
    public function getRepository(): HCMenuTypeRepository
    {
        return $this->repository;
    }
}