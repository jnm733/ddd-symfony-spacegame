<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\Aggregate;

use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;

class Timestamps
{
    private DateTimeValueObject $createdAt;
    private DateTimeValueObject $updatedAt;
    private ?DateTimeValueObject $deletedAt;

    public function __construct(
        DateTimeValueObject $createdAt=null,
        DateTimeValueObject $updatedAt=null,
        DateTimeValueObject $deletedAt=null
    ){
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function createdAt(): DateTimeValueObject {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeValueObject {
        return $this->updatedAt;
    }

    public function deletedAt(): ?DateTimeValueObject {
        return $this->deletedAt;
    }

    public function setUpdatedAt(DateTimeValueObject $value): void {
        $this->updatedAt = $value;
    }
}
