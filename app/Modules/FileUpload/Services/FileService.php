<?php

namespace App\Modules\FileUpload\Services;

use App\Models\EnvironmentMeta;
use App\Modules\Auth\Repositories\EnvironmentRepository;
use App\Modules\FileUpload\Exceptions\FileUploadException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use stdClass;

class FileService
{
    private EnvironmentRepository $environmentRepository;

    public function __construct(EnvironmentRepository $environmentRepository)
    {
        $this->environmentRepository = $environmentRepository;
    }

    public function uploadCv(UploadedFile $file, int $environmentId)
    {
        $filename = $file->storeAs(
            'cv',
            self::generateSecureCvHash($environmentId),
            ['disk' => 'local']
        );

        if (!$filename) {
            throw new FileUploadException('File not specified!');
        }

        $existingFilename = $this->environmentRepository->getCv($environmentId);
        if ($existingFilename) {
            $existingFilename->delete();
        }

        // TODO: Move to factory?
        $newMetaEntry = new EnvironmentMeta;
        $newMetaEntry->key = EnvironmentMeta::KEY_CV_FILENAME;
        $newMetaEntry->value = $filename;
        $newMetaEntry->environment_id = $environmentId;

        $newMetaEntry->save();
    }

    public function getCvDownloadUrl(int $environmentId)
    {
        /** @var stdClass|null $row */
        $row = $this->environmentRepository->getCv($environmentId)->first() ?? null;
        if (!isset($row->value)) {
            return null;
        }

        return "{$row->value}";
    }

    public function getCvLastModifiedAt(int $environmentId)
    {
        /** @var stdClass|null $row */
        $row = $this->environmentRepository->getCv($environmentId)->first() ?? null;
        if (!isset($row->value)) {
            return null;
        }

        return date('d-m-Y', Storage::lastModified($row->value));
    }

    public static function generateSecureCvHash(int $environmentId): string
    {
        return md5("cv-{$environmentId}-}" . microtime()) . '.pdf';
    }

    public function deleteCv(int $environmentId)
    {
        $row = $this->environmentRepository->getCv($environmentId)->first() ?? null;
        if (!isset($row->value)) {
            return null;
        }

        Storage::delete($row->value);

        $this->environmentRepository->getCv($environmentId)->delete();

        return true;
    }
}
