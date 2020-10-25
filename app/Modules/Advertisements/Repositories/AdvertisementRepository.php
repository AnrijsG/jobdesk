<?php


namespace App\Modules\Advertisements\Repositories;


use App\Models\AdvertisementModel;
use App\Repositories\BaseRepository;

class AdvertisementRepository extends BaseRepository
{
    public function tableName(): string
    {
        return 'advertisements';
    }

    /**
     * @param string $category
     * @param int $limit
     * @return AdvertisementModel[]
     */
    public function getAll(string $category = '', int $limit = 0): array
    {
        $query = $this->all(['category', 'LIKE', "$category%"], true);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->all();
    }
}
