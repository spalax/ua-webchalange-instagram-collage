<?php
namespace Frontend\Options;

use Frontend\Options\Exception\DirectoryNotWritableOrNotExistsException;
use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $collageSavePath = '';

    /**
     * @var string
     */
    protected $collageHttpPath = '';

    /**
     * @var string
     */
    protected $collageExtension = '';

    /**
     * @return string
     */
    public function getCollageSavePath()
    {
        return $this->collageSavePath;
    }

    /**
     * @param string $collageSavePath
     *
     * @return ModuleOptions
     */
    protected function setCollageSavePath($collageSavePath)
    {
        if (!is_dir($collageSavePath) || !is_writable($collageSavePath)) {
            throw new DirectoryNotWritableOrNotExistsException('Directory '.$collageSavePath.' is not exists or is not writable by php');
        }
        $this->collageSavePath = realpath($collageSavePath);

        return $this;
    }

    /**
     * @return string
     */
    public function getCollageHttpPath()
    {
        return $this->collageHttpPath;
    }

    /**
     * @param string $collageHttpPath
     *
     * @return ModuleOptions
     */
    protected function setCollageHttpPath($collageHttpPath)
    {
        $this->collageHttpPath = $collageHttpPath;

        return $this;
    }

    /**
     * @return string
     */
    public function getCollageExtension()
    {
        return $this->collageExtension;
    }

    /**
     * @param string $collageExtension
     *
     * @return ModuleOptions
     */
    protected function setCollageExtension( $collageExtension )
    {
        $this->collageExtension = $collageExtension;

        return $this;
    }
}
