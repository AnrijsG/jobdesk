<?php

namespace App\Modules\FileUpload\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\FileUpload\Exceptions\FileDeleteException;
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

    public function saveLogo(Request $request)
    {
        if (!$request->file('logoFile')) {
            throw new FileUploadException('File not specified!');
        }

        $request->validate(
            [
                'logoFile' => 'required|file|image|max:512|dimensions:max_width=2000,max_height=2000',
            ]
        );

        $this->service->uploadLogoFile($request->file('logoFile'), $request->user()->environment->id);
    }

    public function deleteLogo(Request $request)
    {
        try {
            $this->service->deleteLogo($request->user()->environment->id);
        } catch (\Exception $exception) {
            throw new FileDeleteException('Something went wrong, please try again later');
        }
    }
}
