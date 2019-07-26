<?php

namespace app\services\siteCreator;

use creocoder\flysystem\LocalFilesystem;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;
use PHPHtmlParser\Dom\HtmlNode;
use yii\imagine\Image;

class ImageParser
{
    private const ALLOWED_IMAGES = [
        'jpg' => 'jpg',
        IMAGETYPE_JPEG => 'jpeg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WBMP => 'wbmp',
        IMAGETYPE_XBM => 'xbm',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_BMP => 'bmp'
    ];

    private const IMAGE_PATH = 'images';
    private const IMAGE_WIDTH_MAX = 400;
    private const IMAGE_HEIGHT_MAX = 300;

    public const IMAGE_URL_KEY = 0;
    public const IMAGE_FILENAME_KEY = 1;
    public const LOGGER_PREFIX = 'image-parser';

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
        if (!YII_DEBUG) {
            Image::setImagine(new Imagine()); // install on ubuntu imagick
        }
        $this->fs = \Yii::$app->fs;
    }

    /**
     * @param HtmlNode $domBody
     * @param string $url
     */
    public function parse(HtmlNode $domBody, string $url): void
    {
        \ini_set('memory_limit', YII_DEBUG === true ? '2048M' : '1024M');
        $this->images = [];
        $this->maxSizeImage = null;
        $imageUrl = null;
        $imageExtension = null;

        /** @var HtmlNode[] $images */
        $images = $domBody->find('img');
        foreach ($images as $img) {
            if (($imageData = $this->parseImageByUrl($imageUrl = $img->getAttribute('data-src'), $url)) === null
                && ($imageData = $this->parseImageByUrl($imageUrl = $img->getAttribute('src'), $url)) === null) {
                continue;
            }

            /** @var ImageInterface $image */
            [$image, $imageExtension] = $imageData;
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
                $this->maxSizeImage = [$square, $image, $imageUrl, $imageExtension];
            }
        }

        // finally save the biggest image if nothing was saved within the loop
        if ($this->maxSizeImage !== null && $this->maxSizeImage[0] > 10000) {
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
     * @param string|null $imageUrl
     * @param string $url
     * @return array|null
     */
    private function parseImageByUrl(?string $imageUrl, string $url): ?array
    {
        if (!$imageUrl) {
            return null;
        }

        // first try to get image from raw image url
        $image = null;
        $imageUrl = \htmlspecialchars_decode($imageUrl);
        try {
            $image = $this->createFromSource($imageUrl);

        } catch (\Throwable $e) {
            \Yii::warning("Error on creating image object from the URL '{$imageUrl}' [RAW image url]: {$e->getMessage()}", self::LOGGER_PREFIX);
        }
        if ($image !== null) {
            $imageExtension = \pathinfo($this->normalizeImageUrl($imageUrl), PATHINFO_EXTENSION);
            return [$image, $imageExtension];
        }

        // then try to remove GET params from URL
        $imageUrl = $this->normalizeImageUrl($imageUrl);
        $imageExtension = \pathinfo($imageUrl, PATHINFO_EXTENSION);
        if (!$imageExtension) {
            try {
                $imageExtension = self::ALLOWED_IMAGES[\exif_imagetype($imageUrl)] ?? null;
            } catch (\Throwable $e) {
                \Yii::error("Error on getting image ext: {$e->getMessage()}", self::LOGGER_PREFIX);
                return null;
            }
        }
        if (!\in_array($imageExtension, self::ALLOWED_IMAGES, true)) {
            return null;
        }

        $imageUrl = $this->absoluteImageUrl($imageUrl, $url);
        try {
            $image = $this->createFromSource($imageUrl);

        } catch (\Throwable $e) {
            if ($e->getCode() === 35) {
                try {
                    $image = $this->createFromSource($this->removeHttps($imageUrl));

                } catch (\Throwable $e) {
                    \Yii::error("Error on creating image object from the URL '{$imageUrl}' [code is 35]: {$e->getMessage()}", self::LOGGER_PREFIX);
                    return null;
                }

            } else {
                \Yii::error("Error on creating image object from the URL '{$imageUrl}': {$e->getMessage()}", self::LOGGER_PREFIX);
                return null;
            }
        }

        return [$image, $imageExtension];
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

        try {
            $image->save(\Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . $fileName);

        } catch (\Throwable $e) {
            \Yii::error(__METHOD__ . " : Exception : {$e->getMessage()}", self::LOGGER_PREFIX);
            \Yii::warning("Skip image: {$imageSource}", self::LOGGER_PREFIX);
            return;
        }

        $this->images[] = [
            self::IMAGE_URL_KEY => $imageSource,
            self::IMAGE_FILENAME_KEY => $fileName,
        ];
    }

    /**
     * @param string $imageSource
     * @return ImageInterface
     */
    private function createFromSource(string $imageSource): ImageInterface
    {
        return Image::frame($imageSource, 0, '000');
    }

    /**
     * @param string $imageUrl
     * @return string
     */
    private function normalizeImageUrl(string $imageUrl): string
    {
        foreach (self::ALLOWED_IMAGES as $extension) {
            if (($pos = \strpos($imageUrl, "{$extension}?")) === false
                && ($pos = \strpos($imageUrl, "{$extension}#")) === false) {
                continue;
            }

            $pos += \strlen($extension);
            return \substr($imageUrl, 0, $pos);
        }

        return $imageUrl;
    }

    /**
     * @param string $imageUrl
     * @param string $url
     * @return string
     */
    private function absoluteImageUrl(string $imageUrl, string $url): string
    {
        if (\strpos($imageUrl, 'http') === 0) {
            return $imageUrl;
        }
        if ($imageUrl[0] !== '/') {
            $imageUrl = '/' . $imageUrl;
        }

        $scheme = \parse_url($url, PHP_URL_SCHEME);
        $host = \parse_url($url, PHP_URL_HOST);

        // for URLs like //assets3.*.com/v1/image/1631743/size/tmg-article_default_mobile;jpeg_quality=20.jpg
        if ($imageUrl[0] . $imageUrl[1] === '//') {
            return "http:{$imageUrl}"; // better for now
            return "{$scheme}:{$imageUrl}";
        }

        return "{$scheme}://{$host}{$imageUrl}";
    }

    /**
     * @param string $url
     * @return string
     */
    private function removeHttps(string $url): string
    {
        return \str_replace('https', 'http', $url);
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