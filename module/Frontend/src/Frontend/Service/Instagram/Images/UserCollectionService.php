<?php
namespace Frontend\Service\Instagram\Images;

use Frontend\Data\Gallery\NameInterface;
use Frontend\Wrapper\API\InstagramWrapperInterface;

class UserCollectionService extends AbstractCollectionService
{
    /**
     * @var NameInterface
     */
    protected $nameData = null;

    /**
     * @param NameInterface $nameData
     * @param InstagramWrapperInterface $instagramWrapper
     */
    public function __construct(NameInterface $nameData,
                                InstagramWrapperInterface $instagramWrapper)
    {
        $this->nameData = $nameData;
        parent::__construct($instagramWrapper);
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    protected function fetch( $limit )
    {
        return $this->instagramWrapper
                    ->getUserMedia(is_null($this->nameData->getUsername()) ?
                                    'self' :
                                        $this->nameData->getUsername(), $limit);
    }
}
