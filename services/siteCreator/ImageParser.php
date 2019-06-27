<?php

namespace app\services\siteCreator;

use creocoder\flysystem\LocalFilesystem;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use PHPHtmlParser\Dom\HtmlNode;
use yii\imagine\Image;

class ImageParser
{
    private const IMAGE_PATH = 'images';
    private const IMAGE_WIDTH_MAX = 400;
    private const IMAGE_HEIGHT_MAX = 300;

    public const IMAGE_URL_KEY = 0;
    public const IMAGE_FILENAME_KEY = 1;

    /**
     * @var LocalFilesystem
     */
    private $fs;

    /**
     * @var array
     */
    private $images;

    /**
     * @var array
     */
    private $maxSizeImage;

    /**
     * @var string
     */
    private $domain;

    /**
     * ImageParser constructor.
     */
    public function __construct()
    {
        $this->fs = \Yii::$app->fs;
    }

    /**
     * @param HtmlNode $domBody
     * @param string $url
     * @param string|null $domain
     */
    public function parse(HtmlNode $domBody, string $url, ?string $domain = null): void
    {
        $this->images = [];
        $this->maxSizeImage = null;
        $imageSource = null;
        $imageExtension = null;

        /** @var HtmlNode[] $images */
        $images = $domBody->find('img');
        foreach ($images as $img) {
            $imageSource = $img->getAttribute('src');
            if (!$imageSource) {
                continue;
            }

            $imageExtension = \pathinfo($imageSource, PATHINFO_EXTENSION);
            if (!\in_array($imageExtension, ['jpg', 'jpeg', 'png', 'gif'], true)) {
                continue;
            }

            $imageSource = $this->normalizeImageUrl($imageSource, $url);
            try {
                $image = Image::frame($imageSource, 0, '000');
            } catch (\Throwable $e) {
                continue;
            }

            /** @var Box $size */
            $size = $image->getSize();
            // it means that we get the first result that under our size. but it is not the best way. better to get the biggest image
//            if ($size->getWidth() > self::IMAGE_WIDTH_MAX || $size->getHeight() > self::IMAGE_HEIGHT_MAX) {
//                $image->resize(new Box(self::IMAGE_WIDTH_MAX, self::IMAGE_HEIGHT_MAX));
//                $this->saveImage($image, $imageSource, $imageExtension);
//                return;
//            }

            $square = $size->getWidth() * $size->getHeight();
            if ($this->maxSizeImage === null || $square > $this->maxSizeImage[0]) {
                $this->maxSizeImage = [$square, $image, $imageSource, $imageExtension];
            }
        }

        // finally save the biggest image if nothing was saved within the loop
        if ($this->maxSizeImage !== null) {
            $this->maxSizeImage[1]->resize(new Box(self::IMAGE_WIDTH_MAX, self::IMAGE_HEIGHT_MAX));
            $this->saveImage($this->maxSizeImage[1], $this->maxSizeImage[2], $this->maxSizeImage[3]);
        }
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param ImageInterface $image
     * @param string $imageSource
     * @param string $imageExtension
     */
    private function saveImage(ImageInterface $image, string $imageSource, string $imageExtension): void
    {
        $filePath = \implode(DIRECTORY_SEPARATOR, [
            'storage',
            $this->domain,
            self::IMAGE_PATH,
        ]);
        $fileName = $filePath . DIRECTORY_SEPARATOR . \md5($imageSource) . '.' . $imageExtension;
        $this->fs->createDir($filePath);

        if ($image->save(\Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $fileName)) {
            $this->images[] = [
                self::IMAGE_URL_KEY => $imageSource,
                self::IMAGE_FILENAME_KEY => $fileName
            ];
        }
    }

    /**
     * @param string $imageUrl
     * @param string $url
     * @return string
     */
    private function normalizeImageUrl(string $imageUrl, string $url): string
    {
        if (\strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        if ($imageUrl[0] !== '/') {
            $imageUrl = '/' . $imageUrl;
        }

        $scheme = \parse_url($url, PHP_URL_SCHEME);
        $host = \parse_url($url, PHP_URL_HOST);
        return "{$scheme}://{$host}{$imageUrl}";
    }

    /**
     * @param string $domain
     * @return ImageParser
     */
    public function setDomain(string $domain): ImageParser
    {
        $this->domain = $domain;
        return $this;
    }
}