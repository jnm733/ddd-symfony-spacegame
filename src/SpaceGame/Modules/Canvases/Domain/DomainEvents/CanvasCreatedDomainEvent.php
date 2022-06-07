<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\DomainEvents;

use MyProject\Shared\Modules\Domain\Aggregate\DomainEvent;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

final class CanvasCreatedDomainEvent extends DomainEvent {

    public static string $EVENT_NAME = 'canvas.created';

    private CanvasName $name;
    private CanvasWidth $width;
    private CanvasHeight $height;

    public function __construct(
        UuidValueObject $canvasId,
        CanvasName $name,
        CanvasWidth $width,
        CanvasHeight $height,
        string $eventId = null,
        int $unixOccurredOn = null,
        array $metaInfo = null
    ){
        parent::__construct(CanvasCreatedDomainEvent::$EVENT_NAME, $canvasId->value(), $eventId, $unixOccurredOn, $metaInfo);

        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
    }

    public function toPrimitivesProps(): array {
        return [
            'name'   => $this->name->value(),
            'width'  => $this->width->value(),
            'height' => $this->height->value(),
        ];
    }

    public static function fromPrimitives(array $primitives): self {
        return new self($primitives['attributes']['id'],
            $primitives['attributes']['props']['name'],
            $primitives['attributes']['props']['width'],
            $primitives['attributes']['props']['height'],
            $primitives['id'],
            $primitives['occurredOn'],
            $primitives['meta'],
        );
    }
}
