<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Infrastructure\Mappers;

use MyProject\Shared\Modules\Domain\Aggregate\Timestamps;
use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\Obstacle;
use MyProject\SpaceGame\Modules\Canvases\Domain\Spaceship;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

final class CanvasMysqlMapper implements CanvasMapper {

    public static function fromPrimitives(array $primitives): Canvas {

        $spaceship = ($primitives['spaceship']) ? Spaceship::create(
            CanvasObjectCoordinatePosition::create(intval($primitives['spaceship']['x_position'])),
            CanvasObjectCoordinatePosition::create(intval($primitives['spaceship']['y_position'])),
            UuidValueObject::create($primitives['spaceship']['id']),
            new Timestamps(
                ($primitives['spaceship']['created_at']) ? DateTimeValueObject::createFromFormat($primitives['spaceship']['created_at'], 'Y-m-d H:i:s'): null,
                ($primitives['spaceship']['updated_at']) ? DateTimeValueObject::createFromFormat($primitives['spaceship']['updated_at'], 'Y-m-d H:i:s'): null,
                ($primitives['spaceship']['deleted_at']) ? DateTimeValueObject::createFromFormat($primitives['spaceship']['deleted_at'], 'Y-m-d H:i:s'): null
            )
        ) : null;

        $obstacles = ($primitives['obstacles']) ? array_map(function ($obstacle) {
            return Obstacle::create(
                CanvasObjectCoordinatePosition::create(intval($obstacle['x_position'])),
                CanvasObjectCoordinatePosition::create(intval($obstacle['y_position'])),
                UuidValueObject::create($obstacle['id']),
                new Timestamps(
                    ($obstacle['created_at']) ? DateTimeValueObject::createFromFormat($obstacle['created_at'], 'Y-m-d H:i:s'): null,
                    ($obstacle['updated_at']) ? DateTimeValueObject::createFromFormat($obstacle['updated_at'], 'Y-m-d H:i:s'): null,
                    ($obstacle['deleted_at']) ? DateTimeValueObject::createFromFormat($obstacle['deleted_at'], 'Y-m-d H:i:s'): null
                )
            );
        }, $primitives['obstacles']) : [];

        $canvas = Canvas::createGenerated(
            CanvasName::create($primitives['name']),
            CanvasWidth::create(intval($primitives['width'])),
            CanvasHeight::create(intval($primitives['height'])),
            $spaceship,
            $obstacles,
            UuidValueObject::create($primitives['id']),
            new Timestamps(
                ($primitives['created_at']) ? DateTimeValueObject::createFromFormat($primitives['created_at'], 'Y-m-d H:i:s'): null,
                ($primitives['updated_at']) ? DateTimeValueObject::createFromFormat($primitives['updated_at'], 'Y-m-d H:i:s'): null,
                ($primitives['deleted_at']) ? DateTimeValueObject::createFromFormat($primitives['deleted_at'], 'Y-m-d H:i:s'): null
            )
        );

        return $canvas;
    }

    public static function toPrimitives(Canvas $canvas): array {
        $primitives = $canvas->toPrimitives();
        return [
            'id' => $primitives['id'],
            'name' => $primitives['name'],
            'width' => $primitives['width'],
            'height' => $primitives['height'],
            'created_at' => DateTimeValueObject::createFromUnix($primitives['createdAt'])->toFormat('Y-m-d H:i:s'),
            'updated_at' => DateTimeValueObject::createFromUnix($primitives['updatedAt'])->toFormat('Y-m-d H:i:s'),
            'deleted_at' => ($primitives['deletedAt']) ? DateTimeValueObject::createFromUnix($primitives['deletedAt'])->toFormat('Y-m-d H:i:s') : null,
            'spaceship' => ($primitives['spaceship']) ? [
                'id' => $primitives['id'],
                'id_canvas' => $primitives['id'],
                'x_position' => $primitives['spaceship']['xPosition'],
                'y_position' => $primitives['spaceship']['yPosition'],
                'created_at' => DateTimeValueObject::createFromUnix($primitives['spaceship']['createdAt'])->toFormat('Y-m-d H:i:s'),
                'updated_at' => DateTimeValueObject::createFromUnix($primitives['spaceship']['updatedAt'])->toFormat('Y-m-d H:i:s'),
                'deleted_at' => ($primitives['spaceship']['deletedAt']) ? DateTimeValueObject::createFromUnix($primitives['spaceship']['deletedAt'])->toFormat('Y-m-d H:i:s') : null,
            ] : null,
            'obstacles' => ($primitives['obstacles']) ? array_map(function ($obstacle) use ($primitives) {
                return [
                    'id' => $obstacle['id'],
                    'id_canvas' => $primitives['id'],
                    'x_position' => $obstacle['xPosition'],
                    'y_position' => $obstacle['yPosition'],
                    'created_at' => DateTimeValueObject::createFromUnix($obstacle['createdAt'])->toFormat('Y-m-d H:i:s'),
                    'updated_at' => DateTimeValueObject::createFromUnix($obstacle['updatedAt'])->toFormat('Y-m-d H:i:s'),
                    'deleted_at' => ($obstacle['deletedAt']) ? DateTimeValueObject::createFromUnix($obstacle['deletedAt'])->toFormat('Y-m-d H:i:s') : null,
                ];
            }, $primitives['obstacles']) : null,
        ];
    }

}
