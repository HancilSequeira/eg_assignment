<?php

namespace App\Utilities;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService{
    /**
     * @var \ValidatorInterface
     */
    private $validator;

    /**
     * Validator Service Constructor with Dependency Injection
     * @param ValidatorInterface $validator
     */
    
      public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    /**
     * Validate Request and return error Message array
     * @param  object $requestDtoObj
     * @return array  $errorMessage
     */
    
    public function validateRequest($requestDtoObj):array
    {
       
        $errors = $this->validator->validate($requestDtoObj);
        
        $errorMessage = [];
        
        if(count($errors) > 0){
            foreach ($errors as $key=>$violation){
                $errorMessage[$key]['code']='';
                $errorMessage[$key]['feild'] = $violation->getPropertyPath();
                $errorMessage[$key]['message'] = $violation->getMessage();
            }
        }
        return $errorMessage;   
    }
}

