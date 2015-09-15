<?php
namespace Frontend\Data\Gallery;

interface QualityInterface
{
    const QUALITY_THUMBNAIL = 'thumbnail';
    const QUALITY_LOW_RES = 'low_resolution';
    const QUALITY_STANDARD_RES = 'standard_resolution';

    /**
     * @return string
     */
    public function getQuality();
}
