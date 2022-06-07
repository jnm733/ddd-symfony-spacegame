<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\Tests\Shared\Modules\Infrastructure\StringMother;

final class CanvasNameMother {

    public static function create(string $name): CanvasName {
        return CanvasName::create($name);
    }

    public static function random(): CanvasName {
        return CanvasName::create(StringMother::randomInRange(5, 60));
    }


}