<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use App\DTO\VehicleDTO;
use App\DTO\AbstractEntityDto;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'relation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleOwner $vehicleOwner = null;
    
    public function __construct(?VehicleDTO $dto = null) {
        
        if (null !== $dto) {
            $this->applyChanges($dto);            
        }
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id): static 
    {
        $this->id = $id;
        
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getVehicleOwner(): ?VehicleOwner
    {
        return $this->vehicleOwner;
    }

    public function setVehicleOwner(VehicleOwner $vehicleOwner): static
    {
        $this->vehicleOwner = $vehicleOwner;

        return $this;
    }
    
    public function toArray():array {
        
        return get_object_vars($this);
                
    }

    public function applyChanges(VehicleDTO $dto):void {
        
        $this->setBrand($dto->getBrand());
        $this->setModel($dto->getModel());
        $this->setYear($dto->getYear());
        
    }

}
