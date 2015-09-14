<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;

class CollageData extends ConfigurationData
{
    /**
     * @param Request $request
     * @param Di $di
     */
    public function __construct(Request $request, Di $di)
    {
        parent::__construct($request, $di);
        $inputs = $this->getInputs();

        $inputs['width']->setRequired(true);
        $inputs['height']->setRequired(true);

        $this->setData($this->initDefaults($request->getPost()));
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->getValue('width');
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->getValue('height');
    }
}
