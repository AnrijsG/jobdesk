<?php

namespace App\Modules\Advertisements\Structures;

class AdvertisementQueryItem
{
    public string $category = '';

    public string $title = '';

    public string $location = '';

    public int $environmentId = 0;

    public int $limit = 0;

    public int $offset = 0;

    /**
     * @param array $searchItem
     * @return static
     */
    public static function fromArray(array $searchItem): self
    {
        $newSearchItem = new self;
        $newSearchItem->category = $searchItem['category'] ?? '';
        $newSearchItem->title = $searchItem['title'] ?? '';
        $newSearchItem->location = $searchItem['location'] ?? '';
        $newSearchItem->limit = $searchItem['limit'] ?? 0;
        $newSearchItem->offset = $searchItem['offset'] ?? 0;

        return $newSearchItem;
    }
}
