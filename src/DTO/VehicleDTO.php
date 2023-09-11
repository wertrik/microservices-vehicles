<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\VehicleOwnerDTO;

class VehicleDTO implements DtoInterface {
    
    private ?int $id;
    
    #[Assert\NotBlank]
    private ?string $brand = '';

    #[Assert\NotBlank]
    private ?string $model = '';

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $year = null;
    
    #[Assert\NotNull]
    private ?VehicleOwnerDTO $vehicleOwner = null;        
    
    public function getId(): ?int {
        return $this->id;
    }

    public function getBrand(): ?string {
        return $this->brand;
    }

    public function getModel(): ?string {
        return $this->model;
    }

    public function getYear(): ?int {
        return $this->year;
    }
    
    public function getVehicleOwner(): ?VehicleOwnerDTO {        
        return $this->vehicleOwner;        
    }
        
    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setBrand(?string $brand): void {
        $this->brand = $brand;
    }

    public function setModel(?string $model): void {
        $this->model = $model;
    }

    public function setYear(?int $year): void {
        $this->year = $year;
    }
    
    public function setVehicleOwner(VehicleOwnerDTO $vehicleOwner): void {
        $this->vehicleOwner = $vehicleOwner;
    }
    
}
