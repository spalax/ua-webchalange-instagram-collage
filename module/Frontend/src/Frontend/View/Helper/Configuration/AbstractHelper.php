<?php
namespace Frontend\View\Helper\Configuration;

use Zend\View\Helper\AbstractHelper as ZendAbstractHelper;

abstract class AbstractHelper extends ZendAbstractHelper
{
    /**
     * @var mixed
     */
    protected $good = false;

    /**
     * @var string
     */
    protected $goodValue = null;

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
        return $this->goodValue;
    }
}
