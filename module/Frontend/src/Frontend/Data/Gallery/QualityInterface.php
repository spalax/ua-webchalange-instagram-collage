<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;

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
