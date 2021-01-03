<?php

namespace App\Modules\Advertisements\Repositories;

use App\Models\AdvertisementReply;
use App\Repositories\BaseRepository;

class AdvertisementReplyRepository extends BaseRepository
{
    protected function tableName(): string
    {
        return 'advertisement_replies';
    }

    protected function model(): string
    {
        return AdvertisementReply::class;
    }

    /**
     * @param int $userId
     * @param int $advertisementId
     * @return AdvertisementReply|null
     */
    public function getByUserAndAdvertisement(int $userId, int $advertisementId): ?AdvertisementReply
    {
        return $this->all()->where('user_id', $userId)->where('advertisement_id', $advertisementId)->first();
    }

    /**
     * @param int $advertisementId
     * @return AdvertisementReply[]
     */
    public function getByAdvertisement(int $advertisementId): array
    {
        return $this->all()->where('advertisement_id', $advertisementId)->all();
    }
}
