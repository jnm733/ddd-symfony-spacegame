<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain;

use RuntimeException;

use MyProject\Shared\Modules\Domain\Aggregate\AggregateRoot;
use MyProject\Shared\Modules\Domain\Aggregate\Timestamps;
use MyProject\Shared\Modules\Domain\ValueObject\DateTimeValueObject;
use MyProject\Shared\Modules\Domain\ValueObject\UuidValueObject;
use MyProject\SpaceGame\Modules\Canvases\Domain\DomainEvents\CanvasCreatedDomainEvent;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\BusyPositionOnCanvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\ObjectOutsideOfCanvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\ObstacleOnMovePosition;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\SpaceshipNotInitialized;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;

/**
 * Canvas Aggregate Domain Model
 */
final class Canvas extends AggregateRoot {

    private CanvasName $name;
    private CanvasWidth $width;
    private CanvasHeight $height;
    private ?Spaceship $spaceship;
    private array $obstacles;

    private function __construct(UuidValueObject $id=null, Timestamps $timestamps=null) {
        parent::__construct($id, $timestamps);
    }

    //Create a new empty canvas
    public static function createEmpty(
        CanvasName $name,
        CanvasWidth $width,
        CanvasHeight $height,
        UuidValueObject $id=null,
        Timestamps $timestamps=null): self
    {
        $canvas = new self($id, $timestamps);
        $canvas->name = $name;
        $canvas->width = $width;
        $canvas->height = $height;
        $canvas->obstacles = [];
        $canvas->spaceship = null;

        if (!$id) {
            $canvas->addDomainEvent(new CanvasCreatedDomainEvent($canvas->id(), $canvas->name(), $canvas->width(), $canvas->height()));
        }

        return $canvas;
    }

    //Load a created canvas with elements
    public static function createGenerated(
        CanvasName $name,
        CanvasWidth $width,
        CanvasHeight $height,
        Spaceship $spaceship = null,
        array $obstacles = [],
        UuidValueObject $id=null,
        Timestamps $timestamps=null): self
    {
        $canvas = new self($id, $timestamps);
        $canvas->name = $name;
        $canvas->width = $width;
        $canvas->height = $height;
        $canvas->obstacles = [];
        $canvas->spaceship = null;
        if ($spaceship)
            $canvas->setSpaceship($spaceship);
        if ($obstacles) {
            array_map(function ($obstacle) use ($canvas) {
                $canvas->addObstacle($obstacle);
            }, $obstacles);
        }

        return $canvas;
    }

    //Setter for spaceship
    public function setSpaceship(Spaceship $spaceship): void {
        $this->isPositionInCanvasAndNotBusy($spaceship->xPosition(), $spaceship->yPosition());
        $this->spaceship = $spaceship;
    }

    //Set spaceship position
    public function setSpaceshipPosition(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): void {
        //Check if is canvas limits
        $this->isPositionInCanvas($xPosition, $yPosition);

        //Check if is position is busy
        $this->isPositionBusy($xPosition, $yPosition);

        //Move spaceship
        $this->spaceship->setXPosition($xPosition);
        $this->spaceship->setYPosition($yPosition);

        $this->spaceship->updateTimestamp();
    }

    //Init spaceship position
    public function initSpaceshipPosition(): void {
        $initialXPosition = CanvasObjectCoordinatePosition::create(0);
        $initialYPosition = CanvasObjectCoordinatePosition::create(0);

        if (!$this->spaceship)
            $this->spaceship = Spaceship::create($initialXPosition, $initialYPosition);
        else {
            $this->setSpaceshipPosition($initialXPosition, $initialYPosition);
        }
    }

    //Move spaceship to up
    public function moveToUpSpaceship(): void {
        if (!$this->spaceship)
            throw new SpaceshipNotInitialized();

        //Calculate new position
        $actualYPosition = $this->spaceship->yPosition()->value();
        $newYPosition = ($actualYPosition > 0) ? $actualYPosition - 1 : $this->canvasHeightLimit();
        $newYPosition = CanvasObjectCoordinatePosition::create($newYPosition);

        //Check if exists obstacle at this new position
        if ($this->getObstacleAtPosition($this->spaceship->xPosition(), $newYPosition))
            throw new ObstacleOnMovePosition();

        $this->setSpaceshipPosition($this->spaceship->xPosition(), $newYPosition);
    }

    //Move spaceship to right
    public function moveToRightSpaceship(): void {
        if (!$this->spaceship)
            throw new SpaceshipNotInitialized();

        //Calculate new position
        $actualXPosition = $this->spaceship->xPosition()->value();
        $newXPosition = ($actualXPosition < $this->canvasWidthLimit()) ? $actualXPosition + 1 : 0;
        $newXPosition = CanvasObjectCoordinatePosition::create($newXPosition);

        //Check if exists obstacle at this new position
        if ($this->getObstacleAtPosition($newXPosition, $this->spaceship->yPosition()))
            throw new ObstacleOnMovePosition();

        $this->setSpaceshipPosition($newXPosition, $this->spaceship->yPosition());
    }

    //Move spaceship to down
    public function moveToDownSpaceship(): void {
        if (!$this->spaceship)
            throw new SpaceshipNotInitialized();
        
        //Calculate new position
        $actualYPosition = $this->spaceship->yPosition()->value();
        $newYPosition = ($actualYPosition < $this->canvasHeightLimit()) ? $actualYPosition + 1 : 0;
        $newYPosition = CanvasObjectCoordinatePosition::create($newYPosition);

        //Check if exists obstacle at this new position
        if ($this->getObstacleAtPosition($this->spaceship->xPosition(), $newYPosition))
            throw new ObstacleOnMovePosition();

        $this->setSpaceshipPosition($this->spaceship->xPosition(), $newYPosition);
    }

    //Move spaceship to left
    public function moveToLeftSpaceship(): void {
        if (!$this->spaceship)
            throw new SpaceshipNotInitialized();

        //Calculate new position
        $actualXPosition = $this->spaceship->xPosition()->value();
        $newXPosition = ($actualXPosition > 0) ? $actualXPosition - 1 : $this->canvasWidthLimit();
        $newXPosition = CanvasObjectCoordinatePosition::create($newXPosition);

        //Check if exists obstacle at this new position
        if ($this->getObstacleAtPosition($newXPosition, $this->spaceship->yPosition()))
            throw new ObstacleOnMovePosition();

        $this->setSpaceshipPosition($newXPosition, $this->spaceship->yPosition());
    }

    //Init obstacles at random positions
    public function initObstaclesAtRandomPositions(int $numObstacles): void {

        $numCanvasCells = $this->width()->value()*$this->height()->value();
        if ($numObstacles + 1 >= $numCanvasCells)
            throw new RuntimeException('The number of obstacles must be less than the number of cells in the canvas');

        $canvasMatrix = range(0, $numCanvasCells-1);

        //Extract spaceship position
        if ($this->spaceship) {
            $spaceshipPosition = $this->spaceship->yPosition()->value() * $this->width()->value() + ($this->spaceship->xPosition()->value()+1);
            unset($canvasMatrix[($spaceshipPosition > 0) ? $spaceshipPosition - 1 : 0]);
        }

        $obstaclesRandomPositions = array_rand($canvasMatrix, $numObstacles);
        $this->obstacles = [];

        foreach ($obstaclesRandomPositions as $obstacleRandomPosition) {
            $yPosition = ceil(($obstacleRandomPosition+1)/$this->width()->value());
            $yPosition = ($yPosition > 0) ? $yPosition - 1 : 0;

            $xPosition = ($obstacleRandomPosition+1)%$this->width()->value();
            $xPosition = ($xPosition === 0) ? $this->canvasWidthLimit() : $xPosition - 1;

            $this->addObstacleAtPosition(CanvasObjectCoordinatePosition::create(intval($xPosition)), CanvasObjectCoordinatePosition::create(intval($yPosition)));
        }

    }

    //Check if exists obstacle at position
    public function getObstacleAtPosition(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): ?Obstacle {

        if ($this->obstacles) {
            foreach ($this->obstacles as $obstacle) {
                if ($xPosition->isEquals($obstacle->xPosition()) && $yPosition->isEquals($obstacle->yPosition())) {
                    return $obstacle;
                }
            }
        }

        return null;
    }

    //Add obstacle on position
    public function addObstacleAtPosition(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): void {
        $this->isPositionBusy($xPosition, $yPosition);

        $this->isPositionInCanvas($xPosition, $yPosition);

        $this->obstacles[] = Obstacle::create($xPosition, $yPosition);
    }

    //Add created obstacle
    private function addObstacle(Obstacle $obstacle): void {
        $this->isPositionBusy($obstacle->xPosition(), $obstacle->yPosition());

        $this->isPositionInCanvas($obstacle->xPosition(), $obstacle->yPosition());

        $this->obstacles[] = $obstacle;
    }

    //Getter for canvas name
    public function name(): CanvasName {
        return $this->name;
    }

    //Getter for canvas width
    public function width(): CanvasWidth {
        return $this->width;
    }

    //Getter for canvas height
    public function height(): CanvasHeight {
        return $this->height;
    }

    //Getter for canvas spaceship
    public function spaceship(): ?Spaceship {
        return $this->spaceship;
    }

    //Getter for canvas obstacles
    public function obstacles(): array {
        return $this->obstacles;
    }

    //Create canvas from primitives values
    public static function fromPrimitives(object $primitives): self {
        return Canvas::createGenerated(CanvasName::create($primitives->name),
            CanvasWidth::create($primitives->width),
            CanvasHeight::create($primitives->height),
            Spaceship::fromPrimitives($primitives->spaceship),
            ($primitives->obstacles) ? array_map(function ($obstacle) { return Obstacle::fromPrimitives($obstacle); }, $primitives->obstacles) : [],
            UuidValueObject::create($primitives->id),
            new Timestamps(
                ($primitives->createdAt) ? DateTimeValueObject::createFromUnix($primitives->createdAt): null,
                ($primitives->updatedAt) ? DateTimeValueObject::createFromUnix($primitives->updatedAt): null,
                ($primitives->deletedAt) ? DateTimeValueObject::createFromUnix($primitives->deletedAt): null
            )
        );
    }

    //Convert canvas props to primitives values
    public function toPrimitivesProps(): array {
        return [
            'name'      => $this->name->value(),
            'width'     => $this->width->value(),
            'height'    => $this->height->value(),
            'spaceship' => ($this->spaceship) ? $this->spaceship->toPrimitives() : null,
            'obstacles' => ($this->spaceship) ? array_map(function ($obstacle) { return $obstacle->toPrimitives(); }, $this->obstacles) : null
        ];
    }

    //Check if position is in canvas
    private function isPositionInCanvas(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): void {
        if (($xPosition->value() < 0 || $xPosition->value() > $this->canvasWidthLimit()) || ($yPosition->value() < 0 || $yPosition->value() > $this->canvasHeightLimit()))
            throw new ObjectOutsideOfCanvas();
    }

    //Check if position in canvas is busy
    private function isPositionBusy(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): void {
        if ($this->obstacles && $this->getObstacleAtPosition($xPosition, $yPosition) || ($this->spaceship && $xPosition->isEquals($this->spaceship->xPosition()) && $yPosition->isEquals($this->spaceship->yPosition())))
            throw new BusyPositionOnCanvas();
    }

    //Check if position is in canvas and not busy
    private function isPositionInCanvasAndNotBusy(CanvasObjectCoordinatePosition $xPosition, CanvasObjectCoordinatePosition $yPosition): void {
        $this->isPositionInCanvas($xPosition, $yPosition);
        $this->isPositionBusy($xPosition, $yPosition);
    }
    
    //Get width limit of canvas
    private function canvasWidthLimit(): int {
        return $this->width()->value()-1;
    }
    
    //Get height limit of canvas
    private function canvasHeightLimit(): int {
        return $this->height()->value()-1;
    }

}