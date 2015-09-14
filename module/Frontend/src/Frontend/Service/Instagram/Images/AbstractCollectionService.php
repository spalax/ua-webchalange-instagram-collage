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
        $fetchedImages = $this->fetch(40);
        $quality = $limitHexQualityData->getQuality();
        $startTime = time();
        $rec = function ($limit, $rgb, $fetchedImages, $round = 1) use (&$rec, &$images, $quality, $startTime) {
            if ((time() - $startTime) > 15) {
                return;
            }
            if ( is_object( $fetchedImages ) && is_array( $fetchedImages->data ) ) {
                $sorted = [ ];
                foreach ( $fetchedImages->data as $item ) {
                    if ( count( $images ) >= $limit ) {
                        return;
                    }

                    $diff = $this->colorDiff( $rgb, ColorThief::getColor($item->images->{$quality}->url) );

                    $sorted[] = array('i'=>$item, 'diff'=>$diff);
                }

                usort($sorted, function ($a, $b) {
                    if ($a['diff'] == $b['diff']) {
                        return 0;
                    }
                    return ($a['diff'] < $b['diff']) ? -1 : 1;
                });

                foreach ( $sorted as $item ) {
                    if ( $item['i']->type != 'image' ||
                         $item['diff'] >= (400 + ($round*100))
                    ) {
                        continue;
                    }

                    $images[] = $item['i']->images->{$quality};
                }

                return $rec( $limit, $rgb, $this->instagramWrapper->pagination( $fetchedImages, 40 ), $round+1 );
            }
        };

        $rec($limitHexQualityData->getLimit(), $rgb, $fetchedImages);

        return $images;
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

        if ( is_object( $fetchedImages ) && is_array( $fetchedImages->data ) ) {
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
