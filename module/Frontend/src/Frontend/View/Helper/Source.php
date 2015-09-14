<?php
namespace Frontend\View\Helper;

use Frontend\Data\Gallery\QualityInterface;
use Frontend\Data\Gallery\SourceNameInterface;
use Zend\View\Helper\AbstractHelper;

class Source extends AbstractHelper
{
    /**
     * @var mixed
     */
    protected $good = false;

    /**
     * @var string
     */
    protected $lastValue = null;

    public function __invoke($value, $const)
    {
        $this->good = false;
        $this->lastValue = $value;
        
        switch ($const) {
            case 'user':
                if ( $value === SourceNameInterface::SOURCE_USER ) {
                    $this->good = true;
                }
                break;
            case 'feed':
                if ( $value === SourceNameInterface::SOURCE_FEED ) {
                    $this->good = true;
                }
                break;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getChecked()
    {
        return ($this->good ? 'checked' : '');
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return ($this->good ? 'active' : '');
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->lastValue;
    }
}
