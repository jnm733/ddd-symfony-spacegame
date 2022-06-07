<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects;

use RuntimeException;
use MyProject\Shared\Modules\Domain\ValueObject\EnumValueObject;

final class SpaceshipMovementDirection extends EnumValueObject {

    public function __construct(string $value, array $validValues) {
        parent::__construct($value, $validValues);
    }

    protected function throwErrorForInvalidValue() {
        throw new RuntimeException('Invalid direction for spaceship movement');
    }
}
