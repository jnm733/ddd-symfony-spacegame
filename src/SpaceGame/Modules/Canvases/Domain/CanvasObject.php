<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain;

use MyProject\Shared\Modules\Domain\Aggregate\Entity;
use MyProject\Shared\Modules\Domain\Aggregate\Timestamps;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;

/**
 * Abstract class that model an element that is on canvas
 */
abstract class CanvasObject extends Entity {

    private CanvasObjectCoordinatePosition $xPosition;
    private CanvasObjectCoordinatePosition $yPosition;

    protected function __construct(
        CanvasObjectCoordinatePosition $xPosition,
        CanvasObjectCoordinatePosition $yPosition,
        UuidValueObject $id=null, Timestamps $timestamps=null
    ) {
        parent::__construct($id, $timestamps);
        $this->xPosition = $xPosition;
        $this->yPosition = $yPosition;
    }

    public function setXPosition(CanvasObjectCoordinatePosition $xPosition): void {
        $this->xPosition = $xPosition;
    }
    public function xPosition(): CanvasObjectCoordinatePosition {
        return $this->xPosition;
    }

    public function setYPosition(CanvasObjectCoordinatePosition $yPosition): void {
        $this->yPosition = $yPosition;
    }
    public function yPosition(): CanvasObjectCoordinatePosition {
        return $this->yPosition;
    }

    public function toPrimitivesProps(): array {
        return [
            'xPosition'   => $this->xPosition->value(),
            'yPosition'   => $this->yPosition->value(),
        ];
    }

}