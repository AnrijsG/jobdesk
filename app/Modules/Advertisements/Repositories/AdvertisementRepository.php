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
            $query->where(['category' => $item->category]);
        }

        if ($item->title) {
            $query->where('title', 'like', "%$item->title%");
        }

        if ($item->environmentId) {
            $query->where(['environment_id' => $item->environmentId]);
        }

        if ($item->limit) {
            $query->limit($item->limit);
        }

        if ($item->offset) {
            $query->offset($item->offset);
        }

        return $query->orderBy('id', 'desc')->get()->all();
    }

    public function save(AdvertisementModel $advertisementModel)
    {
        return $advertisementModel->save();
    }
}
