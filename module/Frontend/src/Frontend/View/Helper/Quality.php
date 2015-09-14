<?php
namespace Frontend\View\Helper;

use Frontend\Data\Gallery\QualityInterface;
use Zend\View\Helper\AbstractHelper;

class Quality extends AbstractHelper
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
            case 'thumb':
                if ( $value === QualityInterface::QUALITY_THUMBNAIL ) {
                    $this->good = true;
                }
                break;
            case 'low_res':
                if ( $value === QualityInterface::QUALITY_LOW_RES ) {
                    $this->good = true;
                }
                break;
            case 'std_res':
                if ( $value === QualityInterface::QUALITY_STANDARD_RES ) {
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
