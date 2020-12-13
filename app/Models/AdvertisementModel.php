<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
 * @property int $location
 * @property string $apply_url
 * @property int $salary_from
 * @property int $salary_to
 * @property string $expiration_date
 * @property string created_at
 * @property string $updated_at
 *
 * @property-read Environment $environment
 */
class AdvertisementModel extends Model
{
    use HasFactory;

    protected $table = 'advertisements';

    public function toRpc(): array
    {
        return [
            'advertisementId' => $this->id,
            'category' => $this->category,
            'title' => $this->title,
            'content' => $this->content,
            'location' => $this->location,
            'applyUrl' => $this->apply_url,
            'salaryFrom' => $this->salary_from,
            'salaryTo' => $this->salary_to,
            'expirationDate' => $this->expiration_date,
            'environment' => $this->environment->toRpc(),
        ];
    }

    public static function fromArray(array $attributes): AdvertisementModel
    {
        // Hack for saving personal advertisements
        if (!empty($attributes['advertisementId'])) {
            $advertisement = AdvertisementModel::all()->where('id', $attributes['advertisementId'])->first();
        }

        if (!isset($advertisement)) {
            $advertisement = new AdvertisementModel;
        }

        if (!empty($attributes['id'])) {
            $advertisement->id = $attributes['id'];
        }

        $advertisement->environment_id = $attributes['environment_id'] ?? $attributes['environmentId'];
        $advertisement->category = $attributes['category'];
        $advertisement->title = $attributes['title'];
        $advertisement->content = $attributes['content'];
        $advertisement->location = $attributes['location'] ?? $attributes['location'] ?? 0;
        $advertisement->apply_url = $attributes['apply_url'] ?? $attributes['applyUrl'] ?? null;
        $advertisement->salary_from = $attributes['salary_from'] ?? $attributes['salaryFrom'] ?? null;
        $advertisement->salary_to = $attributes['salary_to'] ?? $attributes['salaryTo'] ?? null;
        $advertisement->expiration_date = $attributes['expiration_date'] ?? $attributes['expirationDate'] ?? now();
        $advertisement->created_at = $attributes['created_at'] ?? $attributes['createdAt'] ?? now();
        $advertisement->updated_at = $attributes['updated_at'] ?? $attributes['updatedAt'] ?? now();

        return $advertisement;
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }
}
