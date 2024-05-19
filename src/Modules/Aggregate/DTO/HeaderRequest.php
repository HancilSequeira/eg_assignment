<?php

namespace App\Modules\Aggregate\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Description of Client and access token
 * 
 */
class HeaderRequest {
    /**
     * Define Access Token.
     * @Assert\Type(
     *     type="string",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\NotBlank(message="accessToken field cannot be empty")
     * @Assert\NotNull
    */
    private $accessToken;
    /**
     * Set error message if any, in our case we have Invalid JSON payload
     * which we want to show.
     *
     * @var string
    */
    public $errors = '';
    
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    public function setAccessToken($accessToken)
    {
       $this->accessToken = $accessToken;
    }
    public function getErrors()
    {
        return $this->errors;
    }
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

}
