<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects;

use InvalidArgumentException;
use MyProject\Shared\Modules\Domain\ValueObject\StringValueObject;

final class CanvasName extends StringValueObject {

    private function __construct(string $value) {
        parent::__construct($value);
        $this->ensureIsValidValue($value);
    }

    public static function create(string $value): self {
        return new self($value);
    }

    private function ensureIsValidValue(string $value): void {
        if (!$value)
            throw new InvalidArgumentException('Canvas name is required');
        if (strlen($value) > 75)
            throw new InvalidArgumentException('The number of characters in the canvas name must be less than 75');
    }

}
