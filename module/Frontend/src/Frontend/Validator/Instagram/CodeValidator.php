<?php
namespace Frontend\Validator\Instagram;

use Zend\I18n\Validator\Alnum as AlnumValidator;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class CodeValidator extends AbstractValidator
{
    /**
     * @var AlnumValidator
     */
    protected $alnumValidator = null;


    /**
     * @param AlnumValidator $alnumValidator
     */
    public function __construct(AlnumValidator $alnumValidator)
    {
        $this->alnumValidator = $alnumValidator;
        parent::__construct();
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid( $value )
    {
        if (empty($value) || strlen(trim($value)) == 0) {
            return false;
        }

        if (!$this->alnumValidator->isValid($value)) {
            return false;
        }

        return true;
    }
}
