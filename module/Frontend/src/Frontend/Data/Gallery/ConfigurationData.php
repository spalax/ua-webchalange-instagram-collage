<?php
namespace Frontend\Data\Gallery;

use Zend\Di\Di;
use Zend\Http\PhpEnvironment\Request;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Parameters;

class ConfigurationData extends InputFilter implements SourceNameInterface, LimitHexQualityInterface
{
    /**
     * @param Request $request
     * @param Di $di
     */
    public function __construct(Request $request, Di $di)
    {

        $inputFilter = $this->getFactory()
                            ->createInputFilter([
                                'width' => [
                                    'name'       => 'width',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'digits'],
                                        ['name' => 'between',
                                         'options' => [ 'min' => 320, 'max' => 19200 ]]
                                    ]
                                ],
                                'height' => [
                                    'name'       => 'height',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'digits'],
                                        ['name' => 'between',
                                         'options' => [ 'min' => 320, 'max' => 19200 ]]
                                    ]
                                ],
                                'username' => [
                                    'name'       => 'username',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'not_empty'],
                                        ['name' => 'regex',
                                         'options' => ['pattern' => '/^[a-zA-Z0-9._]+$/']]
                                    ]
                                ],
                                'limit' => [
                                    'name'       => 'limit',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'digits'],
                                        ['name' => 'between',
                                         'options' => [ 'min' => 5, 'max' => 30 ]]
                                    ]
                                ],
                                'hex' => [
                                    'name'       => 'hex',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'hex']
                                    ],
                                    'filters' => [
                                        ['name' => 'callback',
                                         'options' => [
                                             'callback' => function ($value) {
                                                 return ltrim($value, '#');
                                             }
                                         ]]
                                    ]
                                ],
                                'source' => [
                                    'name'       => 'source',
                                    'required'   => true,
                                    'validators' => [
                                        ['name' => 'inarray',
                                         'options' => [
                                             'haystack' => [SourceNameInterface::SOURCE_USER,
                                                            SourceNameInterface::SOURCE_FEED]
                                         ]]
                                    ]
                                ],
                                'quality' => [
                                    'name'       => 'quality',
                                    'required'   => false,
                                    'validators' => [
                                        ['name' => 'inarray',
                                         'options' => [
                                             'haystack' => [QualityInterface::QUALITY_THUMBNAIL,
                                                            QualityInterface::QUALITY_LOW_RES,
                                                            QualityInterface::QUALITY_STANDARD_RES]
                                         ]]
                                    ]
                                ]
                            ]);

        $this->merge($inputFilter);
        $this->setData($this->initDefaults($request->getQuery()));
    }

    /**
     * @param Parameters $params
     *
     * @return Parameters
     */
    protected function initDefaults(Parameters $params)
    {
        if (is_null($params->get('quality')) || !strlen(trim($params->get('quality')))) {
            $params->set('quality', QualityInterface::QUALITY_THUMBNAIL);
        }

        if (is_null($params->get('source')) || !strlen(trim($params->get('source')))) {
            $params->set('source', SourceNameInterface::SOURCE_USER);
        }

        return $params;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->getValue('source');
    }

    /**
     * @return string | null
     */
    public function getUsername()
    {
        if (is_null($this->getValue('username')) || !strlen(trim($this->getValue('username')))) {
            return null;
        }

        return $this->getValue('username');
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        if (is_null($this->getValue('limit')) ||
            !strlen(trim($this->getValue('limit')))) {
            return 5;
        }

        return $this->getValue('limit');
    }

    /**
     * @return string
     */
    public function getQuality()
    {
        if (is_null($this->getValue('quality')) || !strlen(trim($this->getValue('quality')))) {
            return QualityInterface::QUALITY_THUMBNAIL;
        }

        return $this->getValue('quality');
    }

    /**
     * @return string | null
     */
    public function getHex()
    {
        if (is_null($this->getValue('hex')) || !strlen(trim($this->getValue('hex')))) {
            return null;
        }
        return $this->getValue('hex');
    }
}
