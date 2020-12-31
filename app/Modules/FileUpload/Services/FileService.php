<?php

namespace App\Modules\FileUpload\Services;

use App\Models\Environment;
use App\Models\EnvironmentMeta;
use App\Modules\Auth\Repositories\EnvironmentRepository;
use App\Modules\FileUpload\Exceptions\FileUploadException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class FileService
{
    private EnvironmentRepository $environmentRepository;

    public function __construct(EnvironmentRepository $environmentRepository)
    {
        $this->environmentRepository = $environmentRepository;
    }

    /**
     * @param UploadedFile $file
     * @param int $environmentId
     * @throws FileUploadException
     */
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

        $existingFilename = $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_CV_FILENAME);
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

    /**
     * @param UploadedFile $file
     * @param int $environmentId
     */
    public function uploadLogoFile(UploadedFile $file, int $environmentId)
    {
        /** @var Environment $environment */
        $environment = $this->environmentRepository->getById($environmentId);

        $filename = Str::slug($environment->company_name) . '.' . $file->clientExtension();
        $file->storeAs('logo', $filename, ['disk' => 'local']);

        $newMetaEntry = new EnvironmentMeta;
        $newMetaEntry->key = EnvironmentMeta::KEY_COMPANY_LOGO_FILE;
        $newMetaEntry->value = $filename;
        $newMetaEntry->environment_id = $environmentId;

        $newMetaEntry->save();
    }

    /**
     * @param int $environmentId
     * @return string|null
     */
    public function getCvDownloadUrl(int $environmentId)
    {
        /** @var stdClass|null $row */
        $row = $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_CV_FILENAME)->first()
            ?? null;
        if (!isset($row->value)) {
            return null;
        }

        return "{$row->value}";
    }

    /**
     * @param int $environmentId
     * @return false|string|null
     */
    public function getCvLastModifiedAt(int $environmentId)
    {
        /** @var stdClass|null $row */
        $row = $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_CV_FILENAME)->first()
            ?? null;
        if (!isset($row->value)) {
            return null;
        }

        return date('d-m-Y', Storage::lastModified($row->value));
    }

    /**
     * @param int $environmentId
     * @return string
     */
    public static function generateSecureCvHash(int $environmentId): string
    {
        return md5("cv-{$environmentId}-}" . microtime()) . '.pdf';
    }

    /**
     * @param int $environmentId
     * @return bool|null
     */
    public function deleteCv(int $environmentId)
    {
        $row = $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_CV_FILENAME)->first()
            ?? null;
        if (!isset($row->value)) {
            return null;
        }

        Storage::delete($row->value);

        $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_CV_FILENAME)->delete();

        return true;
    }

    /**
     * @param int $environmentId
     */
    public function deleteLogo(int $environmentId): void
    {
        $row = $this->environmentRepository->getMetaRow(
            $environmentId,
            EnvironmentMeta::KEY_COMPANY_LOGO_FILE
        )->first();
        if (!isset($row->value)) {
            return;
        }

        Storage::delete($row->value);

        $this->environmentRepository->getMetaRow($environmentId, EnvironmentMeta::KEY_COMPANY_LOGO_FILE)->delete();
    }
}
