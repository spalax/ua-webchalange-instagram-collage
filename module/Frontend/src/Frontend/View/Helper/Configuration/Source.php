<?php
namespace Frontend\View\Helper\Configuration;

use Frontend\Data\Gallery\SourceNameInterface;

class Source extends AbstractHelper
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
        $this->goodValue = $value;
        
        switch ($const) {
            case 'user':
                $this->goodValue = SourceNameInterface::SOURCE_USER;
                if ( $value === SourceNameInterface::SOURCE_USER ) {
                    $this->good = true;
                }
                break;
            case 'feed':
                $this->goodValue = SourceNameInterface::SOURCE_FEED;
                if ( $value === SourceNameInterface::SOURCE_FEED ) {
                    $this->good = true;
                }
                break;
        }

        return $this;
    }
}
