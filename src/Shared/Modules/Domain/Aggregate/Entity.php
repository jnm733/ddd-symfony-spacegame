<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\Aggregate;

use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;

abstract class Entity
{
    private Timestamps $timestamps;
    private UuidValueObject $id;

    public function __construct(
        UuidValueObject $id = null,
        Timestamps $timestamps = null
    ){
        $this->id = ($id) ?: UuidValueObject::create();
        $now = DateTimeValueObject::now();
        $this->timestamps = ($timestamps) ?: new Timestamps($now, $now);
    }

    final public function id(): UuidValueObject {
        return $this->id;
    }

    final public function timestamps(): Timestamps {
        return $this->timestamps;
    }

    final public function updateTimestamp(DateTimeValueObject $updatedAt=null): void {
        $this->timestamps->setUpdatedAt(($updatedAt) ?: DateTimeValueObject::now());
    }

    public abstract function toPrimitivesProps(): array;

    final public function toPrimitives(): array {
        return array_merge(['id' => $this->id()->value()],
            $this->toPrimitivesProps(),
            ['createdAt'  => ($this->timestamps() && $this->timestamps->createdAt()) ? $this->timestamps()->createdAt()->toUnix() : null,
            'updatedAt'  => ($this->timestamps() && $this->timestamps->updatedAt()) ? $this->timestamps()->updatedAt()->toUnix() : null,
            'deletedAt'  => ($this->timestamps() && $this->timestamps->deletedAt()) ? $this->timestamps()->deletedAt()->toUnix() : null,
        ]);
    }
}
