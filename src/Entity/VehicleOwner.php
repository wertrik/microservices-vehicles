<?php

namespace App\Entity;

use App\Repository\VehicleOwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\DTO\VehicleOwnerDTO;
use App\DTO\AbstractEntityDto;

#[ORM\Entity(repositoryClass: VehicleOwnerRepository::class)]
class VehicleOwner
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'vehicleOwner', targetEntity: Vehicle::class, orphanRemoval: true)]
    private Collection $relation;
    
    public function __construct(?VehicleOwnerDTO $dto = null)
    {

        if (null !== $dto){
            
            $this->setName($dto->getName())
                 ->setAddress($dto->getAddress());
            
        }

        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Vehicle $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setVehicleOwner($this);
        }

        return $this;
    }

    public function removeRelation(Vehicle $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getVehicleOwner() === $this) {
                $relation->setVehicleOwner(null);
            }
        }

        return $this;
    }   
    
    public function applyChanges(VehicleOwnerDTO $dto):void {
        
        $this->setName($dto->getName());
        $this->setAddress($dto->getAddress());
        
    }
    
    public function toArray():array {        
        return get_object_vars($this);                
    }
    
}
