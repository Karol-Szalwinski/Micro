<?php

namespace MicroBundle\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileUploader
 * @package AppBundle\Service
 */
class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadWithName(UploadedFile $file, $fileName)

    {
        $extension = $file->guessExtension();
        if ($fileName == null) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $extension;

        } else {
            $fileName .= '.' . $extension;
        }


        try {
            $file->move($this->getDownloadDirectory($extension), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function upload(UploadedFile $file)
    {

        return $this->uploadWithName($file, null);
    }


    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    private function getDownloadDirectory($extension){
        switch ($extension) {
            case "jpg":
                $subDirectory = '/images';
                break;
            case "jpeg":
                $subDirectory = '/images';
                break;
            case "png":
                $subDirectory = '/images';
                break;
            case "gif":
                $subDirectory = '/images';
                break;
            case "pdf":
                $subDirectory = '/pdfs';
                break;
            default:
                $subDirectory = '';

        }

        return $this->getTargetDirectory() . $subDirectory;

    }
}