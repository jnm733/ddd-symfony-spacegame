<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;

final class CanvasHeightMother {

    public static function create(int $height): CanvasHeight {
        return CanvasHeight::create($height);
    }

    public static function random(): CanvasHeight {
        return CanvasHeight::create(rand(1, 100));
    }


}