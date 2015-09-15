<?php
namespace Frontend\Service\Instagram\Images;

use Frontend\Data\Gallery\HexInterface;
use Frontend\Data\Gallery\LimitHexInterface;
use Frontend\Data\Gallery\LimitHexQualityInterface;
use Frontend\Wrapper\API\InstagramWrapperInterface;
use ColorThief\ColorThief;

abstract class AbstractCollectionService implements CollectionInterface
{
    /**
     * @var InstagramWrapperInterface
     */
    protected $instagramWrapper = null;

    /**
     * @param InstagramWrapperInterface $instagramWrapper
     */
    public function __construct(InstagramWrapperInterface $instagramWrapper)
    {
        $this->instagramWrapper = $instagramWrapper;
    }

    /**
     * @param int $limit
     *
     * @return mixed
     */
    abstract protected function fetch($limit);

    /**
     * @param array $rgb1
     * @param array $rgb2
     *
     * @return number
     */
    private function colorDiff($rgb1, $rgb2)
    {
        $red1   = hexdec($rgb1[0]);
        $green1 = hexdec($rgb1[1]);
        $blue1  = hexdec($rgb1[2]);

        $red2   = hexdec($rgb2[0]);
        $green2 = hexdec($rgb2[1]);
        $blue2  = hexdec($rgb2[2]);


        return abs($red1 - $red2) + abs($green1 - $green2) + abs($blue1 - $blue2) ;
    }

    /**
     * @param string $hex
     *
     * @return array
     */
    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb; // returns an array with the rgb values
    }

    /**
     * @param int $limit
     * @param string $hex
     *
     * @return array
     */
    protected function searchForImagesByHash(LimitHexQualityInterface $limitHexQualityData)
    {
        $rgb = $this->hex2rgb($limitHexQualityData->getHex());

        $images = array();

        $getLimit = $limitHexQualityData->getLimit()*3;

        $fetchAll = function ($getLimit, &$fetchedImages = null) use (&$fetchAll) {
            if (is_null($fetchedImages)) {
                $images = $this->fetch( $getLimit );
            } else {
                $images = $this->instagramWrapper->pagination($fetchedImages, $getLimit);
            }

            if (!is_null($images) || !empty($images) && !empty($images->meta) && $images->meta->code == '200') {
                if (is_null($fetchedImages)) {
                    $fetchedImages = $images;
                } else {
                    $fetchedImages->pagination = $images->pagination;
                    $fetchedImages->data  = array_merge( $fetchedImages->data, $images->data );
                }
            } else {
                return $fetchedImages;
            }

            if (count($fetchedImages->data) < $getLimit && !empty($images->pagination)) {
                return $fetchAll($getLimit, $fetchedImages);
            }

            return $fetchedImages;
        };

        $quality = $limitHexQualityData->getQuality();
        $count = 0;

        $rec = function ($limit, $rgb, $fetchedImages) use (&$rec, &$images, $quality, &$count, $fetchAll, $getLimit) {
            if ( is_object( $fetchedImages ) && !empty( $fetchedImages->data ) && is_array($fetchedImages->data) ) {
                $sorted = [];
                foreach ( $fetchedImages->data as $item ) {
                    $count++;

                    $diff = $this->colorDiff( $rgb, ColorThief::getColor($item->images->thumbnail->url, 1) );

                    if ($item->type != 'image' ||
                        $diff >= (300 + (ceil($count/10)*100))) {
                        continue;
                    }

                    $sorted[] = ['i' => $item->images->{$quality},
                                 'diff' => $diff];
                }

                usort($sorted, function ($a, $b) {
                    if ($a['diff'] == $b['diff']) {
                        return 0;
                    }
                    return ($a['diff'] < $b['diff']) ? -1 : 1;
                });

                foreach ( $sorted as $item ) {
                    if ( count( $images ) >= $limit ) {
                        return;
                    }

                    $images[] = $item;
                }

                $count = 0;

                unset($fetchedImages->data);
                return $rec( $limit, $rgb, $fetchAll($getLimit, $fetchedImages));
            }
        };

        $rec($limitHexQualityData->getLimit(), $rgb, $fetchAll($getLimit));

        usort($images, function ($a, $b) {
            if ($a['diff'] == $b['diff']) {
                return 0;
            }
            return ($a['diff'] < $b['diff']) ? -1 : 1;
        });

        return array_map(function ($item) {
            return $item['i'];
        }, $images);
    }

    /**
     * @param LimitHexQualityInterface $limitHexQualityData
     *
     * @return array
     */
    public function getImages( LimitHexQualityInterface $limitHexQualityData )
    {
        if (!is_null($limitHexQualityData->getHex())) {
            return $this->searchForImagesByHash($limitHexQualityData);
        }

        $fetchedImages = $this->fetch($limitHexQualityData->getLimit());
        $images = array();

        if ( is_object( $fetchedImages ) && !empty( $fetchedImages->data ) ) {
            foreach ( $fetchedImages->data as $item ) {
                if ( $item->type != 'image' ) {
                    continue;
                }
                $images[] = $item->images->{$limitHexQualityData->getQuality()};
            }
        }

        return $images;
    }
}
