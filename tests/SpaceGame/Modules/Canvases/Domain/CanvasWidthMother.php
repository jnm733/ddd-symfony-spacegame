<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

final class CanvasWidthMother {

    public static function create(int $width): CanvasWidth {
        return CanvasWidth::create($width);
    }

    public static function random(): CanvasWidth {
        return CanvasWidth::create(rand(1, 100));
    }


}