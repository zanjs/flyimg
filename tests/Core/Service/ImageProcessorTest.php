<?php

namespace Tests\Core\Service;

use Core\Entity\Image;
use Tests\Core\BaseTest;

class ImageProcessorTest extends BaseTest
{

    /**
     */
    public function testProcessPNG()
    {
        $image = $this->coreManager->processImage(parent::OPTION_URL.',o_png', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::PNG_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessWebpFromPng()
    {
        $image = $this->coreManager->processImage(parent::OPTION_URL.',o_webp', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::WEBP_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessJpgFromPng()
    {
        $image = $this->coreManager->processImage(parent::OPTION_URL.',o_jpg', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::JPEG_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessGifFromPng()
    {
        $image = $this->coreManager->processImage(parent::OPTION_URL.',o_gif', parent::PNG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::GIF_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessJpg()
    {
        $image = $this->coreManager->processImage(parent::OPTION_URL, parent::JPG_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
    }

    /**
     */
    public function testProcessGif()
    {
        $image = $this->coreManager->processImage(parent::GIF_OPTION_URL, parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::GIF_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessPngFromGif()
    {
        $image = $this->coreManager->processImage(parent::GIF_OPTION_URL.',o_png', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::PNG_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessJpgFromGif()
    {
        $image = $this->coreManager->processImage(parent::GIF_OPTION_URL.',o_jpg', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::JPEG_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     */
    public function testProcessWebpFromGif()
    {
        $image = $this->coreManager->processImage(parent::GIF_OPTION_URL.',o_webp', parent::GIF_TEST_IMAGE);
        $this->generatedImage[] = $image;
        $this->assertFileExists($image->getNewFilePath());
        $this->assertEquals(Image::WEBP_MIME_TYPE, $this->getFileMemeType($image->getNewFilePath()));
    }

    /**
     * @param $filePath
     *
     * @return mixed
     */
    protected function getFileMemeType($filePath)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filePath);
    }
}
