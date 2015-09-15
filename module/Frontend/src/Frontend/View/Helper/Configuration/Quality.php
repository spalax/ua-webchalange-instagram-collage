<?php
namespace Frontend\View\Helper\Configuration;

use Frontend\Data\Gallery\QualityInterface;

class Quality extends AbstractHelper
{
    /**
     * @param string $value
     * @param string $const
     *
     * @return $this
     */
    public function __invoke($value, $const)
    {
        $this->good = false;

        switch ($const) {
            case 'thumb':
                $this->goodValue = QualityInterface::QUALITY_THUMBNAIL;
                if ( $value === QualityInterface::QUALITY_THUMBNAIL ) {
                    $this->good = true;
                }
                break;
            case 'low_res':
                $this->goodValue = QualityInterface::QUALITY_LOW_RES;
                if ( $value === QualityInterface::QUALITY_LOW_RES ) {
                    $this->good = true;
                }
                break;
            case 'std_res':
                $this->goodValue = QualityInterface::QUALITY_STANDARD_RES;
                if ( $value === QualityInterface::QUALITY_STANDARD_RES ) {
                    $this->good = true;
                }
                break;
        }

        return $this;
    }
}
