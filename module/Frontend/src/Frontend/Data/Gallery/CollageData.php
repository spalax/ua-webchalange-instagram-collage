<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;

class CollageData extends ConfigurationData
{
    /**
     * @param Request $request
     * @param Di $di
     */
    public function __construct(Request $request, Di $di)
    {
        parent::__construct($request, $di);
        $this->setData($this->initDefaults($request->getPost()));
    }

    /**
     * @return int | null
     */
    public function getWidth()
    {
        if (is_null($this->getValue('width')) || !strlen(trim($this->getValue('width')))) {
            return null;
        }
        return $this->getValue('width');
    }

    /**
     * @return int | null
     */
    public function getHeight()
    {
        if (is_null($this->getValue('height')) || !strlen(trim($this->getValue('height')))) {
            return null;
        }
        return $this->getValue('height');
    }
}
