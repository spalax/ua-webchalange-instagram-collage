<?php
namespace Frontend\Service;

use Imagine\Image\ImagineInterface;

class CollageService
{
    /**
     * @var ImagineInterface|null
     */
    protected $imageService = null;

    /**
     * @param ImagineInterface $imageService
     */
    public function __construct(ImagineInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param int $width
     * @param int $height
     * @param array $images
     * @return string | null
     */
    public function create($width, $height, $images)
    {
        if (count($images) < 1) {
            return null;
        }
        $width = floor($width/$images[0]->width)*$images[0]->width;
        $height = floor($height/$images[0]->height)*$images[0]->height;

        $collage = $this->imageService->create(new \Imagine\Image\Box($width, $height));
        $x = 0;
        $y = 0;
        foreach ($images as $image) {
            $photo = $this->imageService->open($image->url);
            // paste photo at current position
            $collage->paste($photo, new \Imagine\Image\Point($x, $y));

            // move position by 30px to the right
            $x += $image->width;

            if ($x >= $width) {
                // we reached the right border of our collage, so advance to the
                // next row and reset our column to the left.
                $y += $image->height;
                $x = 0;
            }

            if ($y >= $height) {
                break; // done
            }
        }

        return (string)$collage;
    }
}
