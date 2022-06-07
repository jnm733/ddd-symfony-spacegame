<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\Aggregate;

use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;

abstract class DomainEvent
{
    private UuidValueObject $eventId;
    private string $eventName;
    private string $aggregateId;
    private DateTimeValueObject $occurredOn;
    private array $metaInfo;

    public function __construct(
        string $eventName,
        string $aggregateId,
        string $eventId = null,
        int $unixOccurredOn = null,
        array $metaInfo = null
    ){
        $this->eventName = $eventName;
        $this->aggregateId = $aggregateId;
        $this->eventId    = $eventId ?: UuidValueObject::create($eventId);
        $this->occurredOn = ($unixOccurredOn) ? DateTimeValueObject::createFromUnix($unixOccurredOn) : DateTimeValueObject::now();
        $this->metaInfo = $metaInfo ?: [];
    }

    abstract public function toPrimitivesProps(): array;

    abstract public static function fromPrimitives(array $primitives): self;

    final public function toPrimitive(): array {
        return [
            'id' => $this->eventId(),
            'name' => $this->eventName(),
            'occurredOn' => $this->occurredOn(),
            'attributes' => [
                'id' => $this->aggregateId(),
                'props' => $this->toPrimitivesProps()
            ],
            'meta' => $this->metaInfo()
        ];
    }

    final public function aggregateId(): string {
        return $this->aggregateId;
    }

    final public function eventName(): string {
        return $this->eventName;
    }

    final public function eventId(): string {
        return $this->eventId->value();
    }

    final public function occurredOn(): int {
        return $this->occurredOn->toUnix();
    }

    final public function metaInfo(): array {
        return $this->metaInfo;
    }
}
