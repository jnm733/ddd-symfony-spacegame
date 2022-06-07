<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

use InvalidArgumentException;

class FloatValueObject
{
    private float $value;

    protected function __construct(float $value) {
        $this->value = $value;
        $this->ensureIsValidFloat($value);
    }

    public static function create(float $value): self {
        return new self($value);
    }

    public function value(): float {
        return $this->value;
    }

    protected function ensureIsValidFloat(float $value): void {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $value));
        }
    }

}
