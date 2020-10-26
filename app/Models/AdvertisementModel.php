<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdvertisementModel
 * @package App\Models
 *
 * @property int $id
 * @property int $environment_id
 * @property string $category
 * @property string $title
 * @property string $content
 * @property int $city_id
 * @property string $expiration_date
 * @property string created_at
 * @property string $updated_at
 *
 * @property-read Environment $environment
 */
class AdvertisementModel extends Model
{
    public function toRpc(): array
    {
        return [
            'advertisementId' => $this->id,
            'category' => $this->category,
            'title' => $this->title,
            'content' => $this->content,
            'expirationDate' => $this->expirationDate,
            'environment' => $this->environment->toRpc(),
        ];
    }

    public static function fromArray(array $attributes): AdvertisementModel
    {
        $advertisement = new AdvertisementModel;
        $advertisement->id = $attributes['id'];
        $advertisement->environment_id = $attributes['environment_id'];
        $advertisement->category = $attributes['category'];
        $advertisement->title = $attributes['title'];
        $advertisement->content = $attributes['content'];
        $advertisement->city_id = $attributes['city_id'] ?? 0;
        $advertisement->created_at = $attributes['created_at'];
        $advertisement->updated_at = $attributes['updated_at'];

        return $advertisement;
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }
}
