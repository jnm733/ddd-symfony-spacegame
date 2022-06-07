<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Spaceship;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

final class CanvasMother {

    public static function createEmpty(
        CanvasName $name,
        CanvasWidth $width,
        CanvasHeight $height
    ): Canvas {

        return Canvas::createEmpty($name, $width, $height);
    }

    public static function randomEmpty(): Canvas {
        return Canvas::createEmpty(CanvasNameMother::random(),  CanvasWidthMother::random(), CanvasHeightMother::random());
    }

    public static function createGenerated(
        CanvasName $name,
        CanvasWidth $width,
        CanvasHeight $height,
        Spaceship $spaceship,
        array $obstacles): Canvas {

        return Canvas::createGenerated($name, $width, $height, $spaceship, $obstacles);
    }

}