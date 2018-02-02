<?php
namespace HoneyComb\Menu\Database\Seeds;

use HoneyComb\Menu\Repositories\HCMenuTypeRepository;

use Illuminate\Database\Seeder;

class HCMenuTypesSeed extends Seeder
{
    /**
     * @var HCMenuTypeRepository
     */
    private $repository;

    public function __construct(HCMenuTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idList = [
            [
                'id' => 'top-menu',
            ],
            [
                'id' => 'footer-menu',
            ],
            [
                'id' => 'side-menu',
            ],
        ];

        foreach ($idList as $key => $item) {
            $this->repository->updateOrCreate(['id' => $item['id']], $item);
        }

    }
}