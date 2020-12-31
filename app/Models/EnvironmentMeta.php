<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnvironmentMeta
 * @package App\Models
 *
 * @property int $id
 * @property int $environment_id
 * @property string $key
 * @property string $value
 * @property int $created_at
 * @property int $updated_at
 *
 * @property-read Environment $environment
 */
class EnvironmentMeta extends Model
{
    public const KEY_CV_FILENAME = 'CV_FILENAME';
    public const KEY_COMPANY_LOGO_FILE = 'KEY_COMPANY_LOGO_FILE';

    protected $table = 'environment_meta';

    public static function tableName(): string
    {
        return 'environment_meta';
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }
}
