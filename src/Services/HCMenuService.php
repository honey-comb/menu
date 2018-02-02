<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Services;

use HoneyComb\Menu\Repositories\HCMenuRepository;

class HCMenuService
{
    /**
     * @var HCMenuRepository
     */
    private $repository;

    /**
     * HCMenuService constructor.
     * @param HCMenuRepository $repository
     */
    public function __construct(HCMenuRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCMenuRepository
     */
    public function getRepository(): HCMenuRepository
    {
        return $this->repository;
    }
}