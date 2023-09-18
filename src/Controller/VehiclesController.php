<?php

namespace App\Controller;

use App\DTO\VehicleDTO;
use App\DTO\VehicleOwnerDTO;
use App\Entity\Vehicle;
use App\Repository\VehicleOwnerRepository;
use App\Repository\VehicleRepository;
use App\Service\DtoValidator;
use App\Service\Serializer\VehicleOwnerSerializer;
use App\Service\Serializer\VehicleSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class VehiclesController extends AbstractController {
    
    public function __construct(
        private VehicleRepository $vehicleRepository,
        private VehicleOwnerRepository $ownerRepository,
        private VehicleSerializer $serializer,
        private EntityManagerInterface $entityManager,
        private DtoValidator $validator
    ){

    }

    #[Route('/api/vehicleowners/{ownerId}/vehicles/', name: 'api_vehicles_add', methods: 'POST')]
    public function add(int $ownerId, Request $request): JsonResponse {

        $vehicleDto = new VehicleDTO();
        $vehicleOwnerDto = new VehicleOwnerDTO();
        $vehicleOwnerDto->setId($ownerId);
        $vehicleDto->setVehicleOwner($vehicleOwnerDto);
        
        $this->serializer->deserialize($request->getContent(), VehicleDTO::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicleDto]);

        $this->validator->validateOrFail($vehicleOwnerDto, ["referenceToOwner"]);
        $this->validator->validateOrFail($vehicleDto);        

        $vehicleOwner = $this->ownerRepository->find($vehicleOwnerDto->getId());

        if (null === $vehicleOwner) {
            throw new NotFoundHttpException("Not found vehicle owner ID: ".$vehicleOwnerDto->getId());
        }
        
        $vehicle = new Vehicle($vehicleDto);
        $vehicle->setVehicleOwner($vehicleOwner);
        $vehicleOwner->addRelation($vehicle);
                
        $this->entityManager->persist($vehicle);
        $this->entityManager->persist($vehicleOwner);
        $this->entityManager->flush();

        $response = $this->serializer->serialize($vehicle, 'json');
        
        return new JsonResponse($response, Response::HTTP_CREATED, json: true);        
        
    }
    
    #[Route('/api/vehicles/{vehicleId}/', name: 'api_vehicles_get', methods: 'GET')]
    public function getVehicle(int $vehicleId, VehicleOwnerSerializer $serializer): JsonResponse {
        
        $vehicle = $this->vehicleRepository->find($vehicleId);
        
        if (null === $vehicle) {
            throw new NotFoundHttpException("Not found vehicle ID: ".$vehicleId);
        }
        
        $response = $serializer->serialize($vehicle, 'json');
        
        return new JsonResponse($response, Response::HTTP_OK, json: true);
        
    }

    #[Route('/api/vehicleowners/{ownerId}/vehicles/', name: 'api_vehicles_get_vehicles', methods: 'GET')]
    public function getVehicles(int $ownerId): JsonResponse {
        
        $vehicles = $this->vehicleRepository->findBy(["vehicleOwner" => $ownerId]);

        if (empty($vehicles)) {
            throw new NotFoundHttpException("Not found vehicles for owner ID: ".$ownerId);
        }
        
        $response = $this->serializer->serialize($vehicles, 'json');

        return new JsonResponse($response, Response::HTTP_OK, json: true);
        
    }
    
    #[Route('/api/vehicles/{vehicleId}/', name: 'api_vehicles_rm', methods: 'DELETE')]
    #[Route('/api/vehicleowners/{ownerId}/vehicles/{vehicleId}/', methods: 'DELETE')]
    public function remove(int $vehicleId): JsonResponse {

        $qb = $this->vehicleRepository->createQueryBuilder('v');
        
        $qb->delete('App\Entity\Vehicle', 'v')
           ->where('v.id = :identifier')
           ->setParameter('identifier', $vehicleId);
        
        $query = $qb->getQuery();        
        $result = $query->getResult();

        if (0 == $result) {
            throw new NotFoundHttpException("Not found vehicle ID: ".$vehicleId);
        } else {
            return new JsonResponse(["message"=>"Deleted"], Response::HTTP_NO_CONTENT);
        }
        
    }
    
    #[Route('/api/vehicles/{vehicleId}/', name: 'api_vehicles_update', methods: 'PATCH')]
    #[Route('/api/vehicleowners/{ownerId}/vehicles/{vehicleId}/', methods: 'PATCH')]
    public function update(int $vehicleId, Request $request): JsonResponse {
        
        $vehicleDto = new VehicleDTO();
        $vehicleOwnerDto = new VehicleOwnerDTO();
        $vehicle = $this->vehicleRepository->find($vehicleId);
        $requestContent = isset(json_decode($request->getContent(), true)['vehicleOwner']) ? json_decode($request->getContent(), true)['vehicleOwner'] : ["id"=>null] ;

        if (null === $vehicle) {
            throw new EntityNotFoundException("Not found vehicle ID: ".$vehicleId);
        }

        if (is_numeric($requestContent['id']) && $vehicle->getVehicleOwner()->getId() != $requestContent['id']) {
            
            $vehicleOwner = $this->ownerRepository->find($requestContent['id']);
            
            if (null === $vehicleOwner) {
                throw new NotFoundHttpException("Vehicle owner ID: ".$requestContent['id']." was not found.");             
            }

        } else {            
            $vehicleOwner = $vehicle->getVehicleOwner();
        }
        
        $vehicleOwnerDto->setId($vehicleOwner->getId());
        $vehicleDto->setVehicleOwner($vehicleOwnerDto);
        
        // Load original vehicle entity to DTO
        $this->serializer->deserialize(json_encode($vehicle->toArray()), VehicleDTO::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicleDto]);
        // Change DTO according to request
        $this->serializer->deserialize($request->getContent(), VehicleDTO::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vehicleDto]);        

        $this->validator->validateOrFail($vehicleOwnerDto, ["referenceToOwner"]);
        $this->validator->validateOrFail($vehicleDto);
        
        $vehicle->applyChanges($vehicleDto);
        $vehicle->setVehicleOwner($vehicleOwner);

        $this->entityManager->persist($vehicle);
        $this->entityManager->persist($vehicleOwner);
        $this->entityManager->flush();
        
        $response = $this->serializer->serialize($vehicle, 'json');        
        return new JsonResponse($response, Response::HTTP_ACCEPTED, json: true);

        
    }


}
