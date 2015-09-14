<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;

interface LimitHexQualityInterface extends QualityInterface, HexInterface
{
    /**
     * @return int
     */
    public function getLimit();
}
