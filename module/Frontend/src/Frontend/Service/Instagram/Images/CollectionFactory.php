<?php
namespace Frontend\Service\Instagram\Images;

use Frontend\Data\Gallery\SourceNameInterface;
use Zend\Di\Di;

class CollectionFactory
{
    /**
     * @var Di
     */
    protected $di = null;

    /**
     * @param Di $di
     */
    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    /**
     * @param string $source
     *
     * @return CollectionInterface
     */
    public function createCollection(SourceNameInterface $sourceNameData)
    {
        if (!is_null($sourceNameData->getUsername()) ||
            $sourceNameData->getSource() === SourceNameInterface::SOURCE_USER) {
            return $this->di->get('Frontend\Service\Instagram\Images\UserCollectionService',
                                    array('nameData'=>$sourceNameData));
        }

        return $this->di->get('Frontend\Service\Instagram\Images\FeedCollectionService');
    }
}
