<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

use InvalidArgumentException;

class IntegerValueObject
{
    private float $value;

    protected function __construct(int $value) {
        $this->value = $value;
        $this->ensureIsValidFloat($value);
    }

    public static function create(int $value): self {
        return new self($value);
    }

    public function value(): int {
        return intval($this->value);
    }

    public function isEquals(IntegerValueObject $other): bool {
        return $this->value() === $other->value();
    }

    protected function ensureIsValidFloat(int $value): void {
        if (!is_int($value)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $value));
        }
    }

}
