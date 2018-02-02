<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Services;

use HoneyComb\Menu\Repositories\HCMenuGroupRepository;

class HCMenuGroupService
{
    /**
     * @var HCMenuGroupRepository
     */
    private $repository;

    /**
     * HCMenuGroupService constructor.
     * @param HCMenuGroupRepository $repository
     */
    public function __construct(HCMenuGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCMenuGroupRepository
     */
    public function getRepository(): HCMenuGroupRepository
    {
        return $this->repository;
    }
}