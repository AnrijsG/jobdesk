<?php


namespace App\Modules\Advertisements\Repositories;


use App\Models\AdvertisementModel;
use App\Modules\Advertisements\Structures\AdvertisementQueryItem;
use App\Repositories\BaseRepository;

class AdvertisementRepository extends BaseRepository
{
    public function tableName(): string
    {
        return 'advertisements';
    }

    /**
     * @param AdvertisementQueryItem $item
     * @return AdvertisementModel[]
     */
    public function find(AdvertisementQueryItem $item): array
    {
        $query = $this->all();
        if ($item->category) {
            $query->where('category', 'like', "%$item->category%");
        }

        if ($item->environmentId) {
            $query->where(['environment_id' => $item->environmentId]);
        }

        if ($item->limit) {
            $query->limit($item->limit);
        }

        return $query->get()->all();
    }
}
