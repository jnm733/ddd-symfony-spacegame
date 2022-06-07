<?php declare(strict_types=1);

namespace MyProject\Tests\SpaceGame\Modules\Canvases\Application;

use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use MyProject\SpaceGame\Modules\Canvases\Application\CanvasCreator;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\Repositories\CanvasRepository;
use MyProject\Tests\SpaceGame\Modules\Canvases\Domain\CanvasHeightMother;
use MyProject\Tests\SpaceGame\Modules\Canvases\Domain\CanvasNameMother;
use MyProject\Tests\SpaceGame\Modules\Canvases\Domain\CanvasWidthMother;

final class CanvasCreatorTest extends TestCase {

    public function testCreateCanvasTestCase() {

        $loggerMock = $this->loggerMock();
        $mapperMock = $this->mapperMock();
        $repositoryMock = $this->repositoryMock();

        $useCase = new CanvasCreator(
            $loggerMock,
            $mapperMock,
            $repositoryMock);

        $name = CanvasNameMother::random();
        $width = CanvasWidthMother::random()->value();
        $height = CanvasHeightMother::random()->value();

        $loggerMock->shouldReceive('error')->once()->andReturnSelf();
        $repositoryMock->shouldReceive('findCanvasByName')->once();
        $repositoryMock->shouldReceive('saveCanvas')->once();
        $mapperMock->shouldReceive('toPrimitives')->once();

        $canvas = $useCase->run($name->value(), $width, $height, rand(0, intval($width*$height/2)));

    }

    private function loggerMock() {
        return Mockery::mock(LoggerInterface::class);
    }

    private function mapperMock() {
        return Mockery::mock(CanvasMapper::class);
    }

    private function repositoryMock() {
        return Mockery::mock(CanvasRepository::class);
    }

}