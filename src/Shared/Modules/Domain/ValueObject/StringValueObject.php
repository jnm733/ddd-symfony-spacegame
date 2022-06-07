<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

class StringValueObject
{
    private string $value;

    protected function __construct(string $value) {
        $this->value = $value;
    }

    public static function create(string $value): self {
        return new self($value);
    }

    public function value(): string {
        return $this->value;
    }

}
