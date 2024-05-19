<?php

namespace App\Modules\Aggregate\DTO;

use OpenApi\Annotations as OA;
/**
 * Defines Data Transfer Objects for Errors
*/
// @CRC: missing typehinting for getter and setters
class ErrorResponse
{

    
    private $code;

    private $message;

    
    private $description;

    /**
     * List of errors
     *
     * @var string[]
      
     */
    private $errors;

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function __destruct()
    {

    }

}

/**
 * Defines Data Transfer Objects for Errors
 * 
 * @OA\Definition(type="object")
 */
// @CRC: missing typehinting for getter and setters
class Codes
{

    /**
     * Unique error Code
     *
     * @var string
     * @OA\Property(example="2001")
     */
    private $code;

    /**
     * Field Name mapped to error
     *
     * @var string
     * @OA\Property(example="name")
     */
    private $field;

    /**
     * Error message
     *
     * @var string
     * @OA\Property(example="Name field can not be empty")
     */
    private $message;

    /**
     * List of errors
     *
     * @var string[]
     * @OA\Property(
     *      @OA\Items()
     * )
     */
    private $additionalDetails;

    public function getCode()
    {
        return $this->code;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getAdditionalDetails()
    {
        return $this->additionalDetails;
    }

    public function setAdditionalDetails($additionalDetails)
    {
        $this->additionalDetails = $additionalDetails;
    }

}
