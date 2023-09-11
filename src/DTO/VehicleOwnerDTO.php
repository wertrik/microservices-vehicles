<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class VehicleOwnerDTO implements DtoInterface {

    #[Assert\NotNull(groups: ['referenceToOwner','updateOwner'])]
    private ?int $id = null;

    #[Assert\NotBlank(groups: ['newOwner'])]
    private ?string $name = null;

    #[Assert\NotBlank(groups: ['newOwner'])]
    private ?string $address = null;
    
    
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function setAddress(?string $address): void {
        $this->address = $address;
    }
    
    #[Assert\IsTrue(message: 'Owner cretentials are not valid.', groups: ['updateOwner'])]
    public function isOwnerCredentialsValid(): bool
    {
        if (empty($this->name) && empty($this->address)) {
            return false;
        } else {
            return true;
        }
    }
    
}
