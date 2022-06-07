<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Mappers;

use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;

interface CanvasMapper {

    public static function fromPrimitives(array $primitives): Canvas;
    public static function toPrimitives(Canvas $canvas): array;

}
