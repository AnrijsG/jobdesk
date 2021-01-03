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

    protected function model(): string
    {
        return AdvertisementModel::class;
    }

    /**
     * @param AdvertisementQueryItem $item
     * @return AdvertisementModel[]
     */
    public function find(AdvertisementQueryItem $item): array
    {
        $query = $this->buildFindQuery();
        if ($item->category) {
            $query->where(['category' => $item->category]);
        }

        if ($item->title) {
            $query->where('title', 'like', "%{$item->title}%");
        }

        if ($item->location) {
            $query->where('location', 'like', "%{$item->location}%");
        }

        if ($item->environmentId) {
            $query->where(['environment_id' => $item->environmentId]);
        }

        if ($item->onlyActive) {
            $query->where(['is_active' => true]);
        }

        if ($item->onlyAppliableAdvertisements) {
            $query->where(['are_internal_applications_enabled' => true]);
        }

        if ($item->limit) {
            $query->limit($item->limit);
        }

        if ($item->offset) {
            $query->offset($item->offset);
        }

        return $query->orderBy('id', 'desc')->get()->all();
    }

    /**
     * @param int $id
     * @return AdvertisementModel|null
     */
    public function getById(int $id): ?AdvertisementModel
    {
        return $this->all()->where('id', $id)->first();
    }

    public function save(AdvertisementModel $advertisementModel)
    {
        if ($advertisementModel->id) {
            return $advertisementModel->update();
        }

        return $advertisementModel->save();
    }

    /**
     * @return AdvertisementModel[]
     */
    public function getActive(): array
    {
        return $this->all()->where('is_active', true)->all();
    }
}
