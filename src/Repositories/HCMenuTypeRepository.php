<?php

declare(strict_types = 1);

namespace HoneyComb\Menu\Repositories;

use HoneyComb\Menu\Models\HCMenuType;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

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

}