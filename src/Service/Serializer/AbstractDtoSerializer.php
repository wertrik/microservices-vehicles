<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractDtoSerializer implements SerializerInterface {
    
    protected SerializerInterface $serializer;


    public function deserialize($data, $type, $format, $context = []): mixed {
        
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

        return $dto;
        
    }

    public function serialize($data, $format, $context = []): string {
      
        return $this->serializer->serialize($data, $format, $context);
        
    }

}
