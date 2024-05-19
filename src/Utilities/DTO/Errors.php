<?php

namespace App\Utilities\DTO;

/**
 * Defines Data Transfer Objects
 *
 * @author Sayad Ahad
 * @package Metadata
 * 
 * @SWG\Definition(type="object")
 */
class Errors
{
    /**
     * Internal code based on validation error
     * 
     * @var string
     * @SWG\Property(example="400") 
     */
    private $code;

    /**
     * Message based on error code
     * 
     * @var string
     * @SWG\Property(example="Bad Input Request") 
     */
    private $message;

    /**
     * Description on error code
     * 
     * @var string
     * @SWG\Property(example="Data provided in input are not valid") 
     */
    private $description;

    /**
     * List of errors 
     * 
     * @var array
     * @SWG\Property(
     *      @SWG\Items(ref="#/definitions/Codes")
     * ) 
     */
    private $errors;

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}

/**
 * @SWG\Definition(type="object")
 */
class Codes
{
    /**
     * Unique error Code
     * 
     * @var string
     * @SWG\Property(example="2001") 
     */
    private $code;

    /**
     * Field Name mapped to error
     * 
     * @var string
     * @SWG\Property(example="schema") 
     */
    private $field;

    /**
     * Error message
     * 
     * @var string
     * @SWG\Property(example="provided json is not valid") 
     */
    private $message;

    /**
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
