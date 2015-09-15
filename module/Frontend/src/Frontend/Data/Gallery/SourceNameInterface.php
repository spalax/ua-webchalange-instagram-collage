<?php
namespace Frontend\Data\Gallery;

interface SourceNameInterface extends NameInterface
{
    const SOURCE_FEED = 'feed';
    const SOURCE_USER = 'user';

    /**
     * @return string
     */
    public function getSource();
}
