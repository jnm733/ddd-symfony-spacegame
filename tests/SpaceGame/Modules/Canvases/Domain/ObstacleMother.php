<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use MyProject\SpaceGame\Modules\Canvases\Domain\Obstacle;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;

final class ObstacleMother {

    public static function create(
        int $xPosition,
        int $yPosition
    ): Obstacle {
        return Obstacle::create(CanvasObjectCoordinatePosition::create($xPosition), CanvasObjectCoordinatePosition::create($yPosition));
    }

    public static function random(int $canvasWidth, int $canvasHeight): Obstacle {
        return Obstacle::create(CanvasObjectCoordinatePosition::create(rand(0, $canvasWidth)), CanvasObjectCoordinatePosition::create(rand(0, $canvasHeight)));
    }

}