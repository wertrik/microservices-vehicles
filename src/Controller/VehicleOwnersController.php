<?php


namespace App\Controller;

use App\DTO\VehicleOwnerDTO;
use App\Entity\VehicleOwner;
use App\Repository\VehicleOwnerRepository;
use App\Service\DtoValidator;
use App\Service\Serializer\VehicleOwnerSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class VehicleOwnersController extends AbstractController {
    
    public function __construct(
        private VehicleOwnerRepository $repository,
        private VehicleOwnerSerializer $serializer,
        private DtoValidator $validator,
        private EntityManagerInterface $entityManager,
    ) {
        
    }

    
    #[Route('/api/vehicleowners/', name: 'api_owners_add', methods: 'POST')]
    public function add(Request $request):JsonResponse {
        
        $vehicleOwnerDto = $this->serializer->deserialize($request->getContent(), VehicleOwnerDTO::class, 'json');
        
        $this->validator->validateOrFail($vehicleOwnerDto, ['newOwner']);
        
        $vehicleOwner = new VehicleOwner($vehicleOwnerDto);
                
        $this->entityManager->persist($vehicleOwner);
        $this->entityManager->flush();

        $response = $this->serializer->serialize($vehicleOwner, 'json');
        
        return new JsonResponse($response, Response::HTTP_CREATED, json: true); 
        
    }
    
    #[Route('/api/vehicleowners/{ownerId}/', name:'api_owners_get', methods: 'GET')]
    public function get(int $ownerId): JsonResponse {
        
        $vehicleOwner = $this->repository->find($ownerId);
        
        if (null === $vehicleOwner) {
            throw new NotFoundHttpException("Not found vehicle owner ID: ".$ownerId);
        }
        
        $response = $this->serializer->serialize($vehicleOwner, 'json');
        
        return new JsonResponse($response, Response::HTTP_OK, json: true);
        
    }
    
    #[Route('/api/vehicleowners/{ownerId}/', name:'api_owners_rm', methods: 'DELETE')]
    public function remove(int $ownerId, ): JsonResponse {
        
        $vehicleOwner = $this->repository->find($ownerId);
        
        if (null === $vehicleOwner) {
            throw new NotFoundHttpException("Not found vehicle owner ID: ".$ownerId);
        }
        
        $this->entityManager->remove($vehicleOwner);
        $this->entityManager->flush();

        return new JsonResponse(["message"=>"Deleted"], Response::HTTP_NO_CONTENT);
        
    }
    
    #[Route('/api/vehicleowners/{ownerId}/', name:'api_owners_update', methods: 'PATCH')]
    public function update(int $ownerId, Request $request): JsonResponse {
        
        $vehicleOwner = $this->repository->find($ownerId);
        $vehicleOwnerDto = new VehicleOwnerDTO();
        
        if (null === $vehicleOwner) {
            throw new NotFoundHttpException("Not found vehicle owner ID: ".$ownerId);
        }
        
        // Load original owner entity to DTO
        $this->serializer->deserialize(json_encode($vehicleOwner->toArray()), VehicleOwnerDTO::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicleOwnerDto]);
        // Change DTO according to request
        $this->serializer->deserialize($request->getContent(), VehicleOwnerDTO::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicleOwnerDto]);

        $this->validator->validateOrFail($vehicleOwnerDto, ["updateOwner"]);
        
        $vehicleOwner->applyChanges($vehicleOwnerDto);
        
        $this->entityManager->persist($vehicleOwner);
        $this->entityManager->flush();        
        
        $response = $this->serializer->serialize($vehicleOwner, 'json');
        
        return new JsonResponse($response, Response::HTTP_ACCEPTED, json: true);
        
    }
    
}
