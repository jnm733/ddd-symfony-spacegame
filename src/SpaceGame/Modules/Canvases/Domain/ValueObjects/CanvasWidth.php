<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects;

use InvalidArgumentException;
use MyProject\Shared\Modules\Domain\ValueObject\IntegerValueObject;

final class CanvasWidth extends IntegerValueObject {

    protected function __construct(int $value) {
        parent::__construct($value);
        $this->ensureIsValidValue($value);
    }

    public static function create(int $value): self {
        return new self($value);
    }

    private function ensureIsValidValue(int $value): void {
        if ($value <= 0)
            throw new InvalidArgumentException('Canvas width should be greater than zero');
    }

}
