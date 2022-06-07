<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Domain;

use Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\BusyPositionOnCanvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\ObstacleOnMovePosition;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\SpaceshipNotInitialized;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasObjectCoordinatePosition;

final class CanvasUnitTest extends TestCase {

    public function testCanvasShouldEmptyOnCreate(): void {
        $canvas = CanvasMother::randomEmpty();

        $this->assertNotNull($canvas->name());
        $this->assertNotNull($canvas->width());
        $this->assertNotNull($canvas->height());

        $this->assertNull($canvas->spaceship());
        $this->assertEmpty($canvas->obstacles());
    }

    public function testExceptionIfMoreObstaclesThanCells(): void {
        $this->expectException(RuntimeException::class);

        $canvas = CanvasMother::createEmpty(CanvasNameMother::random(), CanvasWidthMother::create(5), CanvasHeightMother::create(5));
        $canvas->initObstaclesAtRandomPositions(25);
    }

    public function testExceptionIfSpaceshipMovementWithoutInit(): void {
        $this->expectException(SpaceshipNotInitialized::class);

        $canvas = CanvasMother::randomEmpty();
        $canvas->moveToUpSpaceship();
    }

    public function testCorrectNumberOfObstacles(): void {
        $canvas = CanvasMother::createEmpty(CanvasNameMother::random(), CanvasWidthMother::create(15), CanvasHeightMother::create(12));
        $canvas->initSpaceshipPosition();
        $canvas->initObstaclesAtRandomPositions(10);

        $this->assertEquals(10, sizeof($canvas->obstacles()));
    }

    public function testCorrectSpaceshipMovement(): void {

        $canvas = CanvasMother::createEmpty(CanvasNameMother::random(), CanvasWidthMother::create(5), CanvasHeightMother::create(5));
        $canvas->initSpaceshipPosition();

        //Move to top limit
        $canvas->moveToUpSpaceship();
        $this->assertEquals(4, $canvas->spaceship()->yPosition()->value());

        //Move to bottom limit
        $canvas->moveToDownSpaceship();
        $this->assertEquals(0, $canvas->spaceship()->yPosition()->value());

        //Move to left limit
        $canvas->moveToLeftSpaceship();
        $this->assertEquals(4, $canvas->spaceship()->xPosition()->value());

        //Move to right limit
        $canvas->moveToRightSpaceship();
        $this->assertEquals(0, $canvas->spaceship()->xPosition()->value());

        //Normal movements
        $canvas->moveToRightSpaceship();
        $this->assertEquals(1, $canvas->spaceship()->xPosition()->value());
        $canvas->moveToDownSpaceship();
        $this->assertEquals(1, $canvas->spaceship()->yPosition()->value());
        $canvas->moveToRightSpaceship();
        $this->assertEquals(2, $canvas->spaceship()->xPosition()->value());
        $canvas->moveToLeftSpaceship();
        $this->assertEquals(1, $canvas->spaceship()->xPosition()->value());
        $canvas->moveToUpSpaceship();
        $this->assertEquals(0, $canvas->spaceship()->yPosition()->value());
    }

    public function testExceptionIfSpaceshipMovToObstacles(): void {

        $canvas = CanvasMother::createGenerated(CanvasNameMother::random(),
            CanvasWidthMother::create(5),
            CanvasHeightMother::create(5),
            SpaceshipMother::create(1, 1),
            [
                ObstacleMother::create(2,1),
                ObstacleMother::create(1,2),
                ObstacleMother::create(0,1),
                ObstacleMother::create(1,3),
            ]
        );

        $this->expectException(ObstacleOnMovePosition::class);
        try {
            $canvas->moveToDownSpaceship();
        } catch (Exception $downException) {
            $this->expectException(ObstacleOnMovePosition::class);
            try {
                $canvas->moveToLeftSpaceship();
            } catch (Exception $leftException) {
                $this->expectException(ObstacleOnMovePosition::class);
                try {
                    $canvas->moveToRightSpaceship();
                } catch (Exception $rightException) {
                    $canvas->moveToUpSpaceship();
                    $this->assertEquals(1, $canvas->spaceship()->xPosition()->value());
                    $this->assertEquals(0, $canvas->spaceship()->yPosition()->value());

                    $canvas->moveToUpSpaceship();
                    $this->assertEquals(1, $canvas->spaceship()->xPosition()->value());
                    $this->assertEquals(4, $canvas->spaceship()->yPosition()->value());

                    $this->expectException(ObstacleOnMovePosition::class);
                    $canvas->moveToUpSpaceship();
                }
            }
        }
    }

    public function testExceptionIfConflictOnCanvasObjectPositions(): void {

        $this->expectException(BusyPositionOnCanvas::class);

        $canvas = CanvasMother::createGenerated(CanvasNameMother::random(),
            CanvasWidthMother::create(5),
            CanvasHeightMother::create(5),
            SpaceshipMother::create(2, 1),
            [
                ObstacleMother::create(2,1),
                ObstacleMother::create(1,2),
                ObstacleMother::create(0,1),
                ObstacleMother::create(1,3),
            ]
        );

        $canvas = CanvasMother::createGenerated(CanvasNameMother::random(),
            CanvasWidthMother::create(5),
            CanvasHeightMother::create(5),
            SpaceshipMother::create(2, 1),
            []
        );

        $this->expectException(BusyPositionOnCanvas::class);
        $canvas->addObstacleAtPosition(CanvasObjectCoordinatePosition::create(2), CanvasObjectCoordinatePosition::create(2));
    }
}
