<?php


namespace App\Service\Serializer;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


class VehicleOwnerSerializer extends AbstractDtoSerializer {    
    
    public function __construct() {

        $this->serializer = new Serializer([new ObjectNormalizer(
                                    defaultContext: [
                                        AbstractNormalizer::IGNORED_ATTRIBUTES => ['relation']]                
                                )], [new JsonEncoder()]);
        
    }

}
