<?php

namespace App\Modules\Aggregate\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of Client and access token
 * 
 */
class HeaderIgnoreRequest {
   
    /**
     * Set error message if any, in our case we have Invalid JSON payload
     * which we want to show.
     *
     * @var string
    */
    public $errors = '';
    public function getErrors()
    {
        return $this->errors;
    }
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

}
