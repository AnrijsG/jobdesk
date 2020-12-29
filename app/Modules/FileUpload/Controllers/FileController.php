<?php

namespace App\Modules\FileUpload\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\FileUpload\Exceptions\FileUploadException;
use App\Modules\FileUpload\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private FileService $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function saveCv(Request $request)
    {
        if (!$request->file('cv')) {
            throw new FileUploadException('File not specified!');
        }

        $request->validate(
            [
                'cv' => "required|file|mimes:pdf|max:2048",
            ]
        );

        $this->service->uploadCv($request->file('cv'), $request->user()->environment->id);
    }

    public function deleteCv(Request $request)
    {
        $this->service->deleteCv($request->user()->environment->id);
    }

    public function getPersonalCvDownloadUrl(Request $request)
    {
        return $this->service->getCvDownloadUrl($request->user()->environment->id);
    }

    public function getPersonalCvModifiedAt(Request $request)
    {
        return $this->service->getCvLastModifiedAt($request->user()->environment->id);
    }
}
