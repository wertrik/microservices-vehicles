<?php

namespace App\Service;

use App\DTO\DtoInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;


class DtoValidator {
    
    public function __construct(
        private ValidatorInterface $validator
    ) {
        
    }

    public function validateOrFail(DtoInterface $dto, array $groups = null) {
        
        $errors = $this->validator->validate($dto, null, $groups);

        if ($errors->count() > 0) {
            
            $response = [];
            
            foreach ($errors AS $error) {
                $response[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new ValidationFailedException("An error occured", $errors);

        }
        
    }
    
}
