<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;

interface SourceNameInterface extends NameInterface
{
    const SOURCE_FEED = 'feed';
    const SOURCE_USER = 'user';

    /**
     * @return string
     */
    public function getSource();
}
