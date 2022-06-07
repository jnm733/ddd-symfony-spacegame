<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidValueObject
{
    protected string $value;

    protected function __construct(string $value) {
        $this->value = $value;
        $this->ensureIsValidUuid($value);
    }

    public static function create(string $uuid=null): self {
        return new self(($uuid) ?: RamseyUuid::uuid4()->toString());
    }

    public function value(): string {
        return $this->value;
    }

    private function ensureIsValidUuid(string $id): void {
        if (!RamseyUuid::isValid($id)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>.', static::class, $id));
        }
    }
}
