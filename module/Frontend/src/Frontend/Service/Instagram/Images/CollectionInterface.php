<?php
namespace Frontend\Service\Instagram\Images;

use Frontend\Data\Gallery\LimitHexQualityInterface;

interface CollectionInterface
{
    /**
     * @param LimitHexQualityInterface $limitHexQualityData
     *
     * @return mixed
     */
    public function getImages( LimitHexQualityInterface $limitHexQualityData );
}
