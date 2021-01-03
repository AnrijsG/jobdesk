<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdvertisementReply
 * @package App\Models
 *
 * @property int $id
 * @property string $cv_download_url
 * @property string $cover_letter
 * @property int $advertisement_id
 * @property int $user_id
 * @property int $created_at timestamp
 * @property int $updated_at timestamp
 *
 * @property-read AdvertisementModel $advertisement
 * @property-read User $user
 */
class AdvertisementReply extends Model
{
    protected $table = 'advertisement_replies';

    public function advertisement()
    {
        return $this->belongsTo(AdvertisementModel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toRpc(): array
    {
        // Only return the necessary data
        return [
            'advertisementReplyId' => $this->id,
            'cvDownloadUrl' => $this->cv_download_url,
            'coverLetter' => $this->cover_letter,
            'userName' => $this->user->name,
            'userEmail' => $this->user->email,
        ];
    }
}
