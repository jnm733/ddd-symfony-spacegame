<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Infrastructure\Mappers;

use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

final class CanvasApiDtoMapper implements CanvasMapper {

    public static function fromPrimitives(array $primitives): Canvas {

        $canvas = Canvas::createEmpty(
            CanvasName::create($primitives['name']),
            CanvasWidth::create($primitives['width']),
            CanvasHeight::create($primitives['height'])
        );

        return $canvas;
    }

    public static function toPrimitives(Canvas $canvas): array {
        $primitives = $canvas->toPrimitives();
        return [
            'name' => $primitives['name'],
            'width' => $primitives['width'],
            'height' => $primitives['height'],
            'spaceship' => ($primitives['spaceship']) ? [
                'x' => $primitives['spaceship']['xPosition'],
                'y' => $primitives['spaceship']['yPosition'],
            ] : null,
            'obstacles' => ($primitives['obstacles']) ? array_map(function ($obstacle) {
                return [
                    'x' => $obstacle['xPosition'],
                    'y' => $obstacle['yPosition'],
                ];
            }, $primitives['obstacles']) : null,
        ];
    }

}
