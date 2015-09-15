<?php
namespace Frontend\Service;

use Frontend\Options\ModuleOptions;
use Imagine\Image\ImagineInterface;

class CollageService
{
    /**
     * @var ImagineInterface
     */
    protected $imageService = null;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions = null;

    /**
     * @param ImagineInterface $imageService
     */
    public function __construct(ImagineInterface $imageService, ModuleOptions $moduleOptions)
    {
        $this->imageService = $imageService;
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @param string $uniqueId
     *
     * @return string
     */
    protected function getFilePath($uniqueId)
    {
        return $this->moduleOptions->getCollageSavePath().'/'.$this->getFileName($uniqueId);
    }

    /**
     * @param string $uniqueId
     *
     * @return string
     */
    protected function getFileName($uniqueId)
    {
        return $uniqueId.'.'.$this->moduleOptions->getCollageExtension();
    }

    /**
     * @param string $uniqueId
     *
     * @return string
     */
    protected function getHttpPath($uniqueId)
    {
        return $this->moduleOptions->getCollageHttpPath().'/'.$this->getFileName($uniqueId);
    }

    /**
     * @param string $uniqueId
     *
     * @return bool
     */
    protected function checkHasCached($uniqueId)
    {
        $filePath = $this->getFilePath($uniqueId);
        if (file_exists($filePath)) {
            if ((time() - filemtime($filePath)) < (60*60)*3) {
                return true;
            }

            unlink($filePath);
        }

        return false;
    }

    /**
     * @param array $images
     * @param string $uniqueId
     * @param int [optional] $width
     * @param int [optional] $height
     * @param int [optional] $limit
     * @return string | false
     */
    public function create($images, $uniqueId, $width = null, $height = null, $limit = 5)
    {
        if (count($images) < 1) {
            return false;
        }
        if ($this->checkHasCached($uniqueId) === true) {
            return $this->getHttpPath($uniqueId);
        }

        if (count($images) < $limit) {
            $limit = count($images);
        }

        $getMax = function ($key) {
            return function ($images, $item) use ($key) {
                $processed = [ ];
                if (is_array($images)) {
                    foreach ( $images as $image ) {
                        if ( $item->{$key} < $image->{$key} ) {
                            $processed[] = $image;
                        }
                    }
                } else {
                    return $images;
                }

                return count($processed) ? $processed : $item->{$key};
            };
        };

        $maxWidth = array_reduce ($images, $getMax('width'), $images);
        $maxHeight = array_reduce ($images, $getMax('height'), $images);

        if ($width === null) {
            $width = ceil(sqrt($limit)) * $maxWidth;
        } else {
            $width = floor( $width / $maxWidth ) * $maxWidth;
        }

        if ($height === null) {
            $sqrt = sqrt($limit);
            $height = (($limit%2) == 0 ? floor($sqrt) : ceil($sqrt)) * $maxHeight;
        } else {
            $height = floor($height / $maxHeight) * $maxHeight;
        }

        $collage = $this->imageService->create(new \Imagine\Image\Box($width, $height));
        $x = 0;
        $y = 0;
        foreach ($images as $image) {
            $photo = $this->imageService->open($image->url);
            // paste photo at current position
            $collage->paste($photo, new \Imagine\Image\Point($x, $y));

            // move position by 30px to the right
            $x += $maxWidth;

            if ($x >= $width) {
                // we reached the right border of our collage, so advance to the
                // next row and reset our column to the left.
                $y += $maxHeight;
                $x = 0;
            }

            if ($y >= $height) {
                break; // done
            }
        }

        try {
            $collage->save( $this->getFilePath( $uniqueId ) );

            return $this->getHttpPath($uniqueId);
        } catch (\Exception $ex) {
            return false;
        }
    }
}
