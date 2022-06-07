<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Repositories;

use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;

interface CanvasRepository {

    public function findCanvasByName(CanvasName $name): ?Canvas;
    public function saveCanvas(Canvas $canvas): void;

}