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

    public function uploadWithNameAndThumbs(UploadedFile $file, $fileFirstName, $thumbs = [])

    {
        $extension = $file->guessExtension();
        $downloadDirectory = $this->getDownloadDirectory($extension);
        if ($fileFirstName == null) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileFirstName = $safeFilename . '-' . uniqid();

        }
        $fileName = $fileFirstName . '.' . $extension;

        try {
            $file->move($downloadDirectory, $fileName);

            foreach ($thumbs as $thumbSize)

                if ($this->isImage($downloadDirectory . "/" . $fileName)) {

                    foreach ($thumbs as $thumbSize) {
                        $this->makeThumb($downloadDirectory . "/" . $fileName, $downloadDirectory . "/thumb" .
                            $thumbSize . "/" . $fileFirstName . "thumb" . $thumbSize . "." . $extension, $thumbSize, $extension);
                    }
                }
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function upload(UploadedFile $file)
    {

        return $this->uploadWithNameAndThumbs($file, null);
    }

    public function uploadWithThumbs(UploadedFile $file, $thumbs)
    {

        return $this->uploadWithNameAndThumbs($file, null, $thumbs);
    }


    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    private function getDownloadDirectory($extension)
    {
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

    private function isImage($path)
    {
        $a = getimagesize($path);
        $image_type = $a[2];
        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            return true;
        }
        return false;
    }

    private function makeThumb($src, $dest, $desired_height, $extension) {
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $this->makeThumbJpeg($src, $dest, $desired_height);
        }
        if ($extension == 'png') {
            $this->makeThumbPng($src, $dest, $desired_height);
        }
    }

    private function makeThumbJpeg($src, $dest, $desired_height)
    {
        /* read the source image */
        $source_image = imagecreatefromjpeg($src);
        $virtual_image = $this->resizeImage($desired_height, $source_image);
        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest);
    }

    private function makeThumbPng($src, $dest, $desired_height)
    {
        /* read the source image */
        $source_image = imagecreatefrompng($src);
        $virtual_image = $this->resizeImage($desired_height, $source_image);
        /* create the physical thumbnail image to its destination */
        imagepng($virtual_image, $dest);
    }

    /**
     * @param $desired_height
     * @param $source_image
     * @return resource
     */
    private function resizeImage($desired_height, $source_image)
    {
        $width = imagesx($source_image);
        $height = imagesy($source_image);
        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_width = floor($width * ($desired_height / $height));
        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
        return $virtual_image;

    }
}