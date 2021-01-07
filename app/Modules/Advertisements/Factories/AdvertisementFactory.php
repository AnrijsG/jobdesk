<?php

namespace App\Modules\Advertisements\Factories;

use App\Models\AdvertisementModel;

class AdvertisementFactory
{
    public function fromArray(array $attributes, ?AdvertisementModel $advertisement = null): AdvertisementModel
    {
        if (!$advertisement) {
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
        $advertisement->salary_from = $attributes['salary_from'] ?? $attributes['salaryFrom'] ?? null;
        $advertisement->salary_to = $attributes['salary_to'] ?? $attributes['salaryTo'] ?? null;
        $advertisement->expiration_date = $attributes['expiration_date'] ?? $attributes['expirationDate'] ?? now();
        $advertisement->is_active = $attributes['is_active'] ?? $attributes['isActive'] ?? true;
        $advertisement->are_internal_applications_enabled = $attributes['are_internal_applications_enabled']
            ?? $attributes['areInternalApplicationsEnabled']
            ?? false;
        $advertisement->created_at = $attributes['created_at'] ?? $attributes['createdAt'] ?? now();
        $advertisement->updated_at = $attributes['updated_at'] ?? $attributes['updatedAt'] ?? now();

        return $advertisement;
    }
}
