<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain;

use MyProject\Shared\Modules\Domain\Aggregate\Timestamps;
use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;

/**
 * Spaceship Domain Model for Canvas Aggregate
 */
final class Spaceship extends CanvasObject {

    public static function create(
        CanvasObjectCoordinatePosition $xPosition,
        CanvasObjectCoordinatePosition $yPosition,
        UuidValueObject                $id=null,
        Timestamps                     $timestamps=null): self
    {
        return new self($xPosition, $yPosition, $id, $timestamps);
    }

    public static function fromPrimitives(object $primitives): self {
        return Spaceship::create(CanvasObjectCoordinatePosition::create($primitives->xPosition),
            CanvasObjectCoordinatePosition::create($primitives->yPosition),
            UuidValueObject::create($primitives->id),
            new Timestamps(
                ($primitives->createdAt) ? DateTimeValueObject::createFromUnix($primitives->createdAt): null,
                ($primitives->updatedAt) ? DateTimeValueObject::createFromUnix($primitives->updatedAt): null,
                ($primitives->deletedAt) ? DateTimeValueObject::createFromUnix($primitives->deletedAt): null
            )
        );
    }

}