<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;

interface HexInterface
{
    /**
     * @return string | null
     */
    public function getHex();
}
