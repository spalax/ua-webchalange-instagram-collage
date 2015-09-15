<?php
namespace Frontend\Data\Gallery;

interface LimitHexQualityInterface extends QualityInterface, HexInterface
{
    /**
     * @return int
     */
    public function getLimit();
}
