<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Application;

use Exception;
use Psr\Log\LoggerInterface;
use MyProject\SpaceGame\Modules\Canvases\Domain\Canvas;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\CanvasAlreadyExists;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\Repositories\CanvasRepository;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasHeight;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasWidth;

/**
 * Use Case to create an empty canvas
 */
final class CanvasCreator {

    private LoggerInterface $logger;
    private CanvasMapper $dataMapper;
    private CanvasRepository $repository;

    public function __construct(
        LoggerInterface $logger,
        CanvasMapper $dataMapper,
        CanvasRepository $repository)
    {
        $this->logger = $logger;
        $this->dataMapper = $dataMapper;
        $this->repository = $repository;
    }

    public function run(
        string $canvasName,
        int $canvasWidth,
        int $canvasHeight,
        int $numObstacles=null): array {

        try {

            //Check if exists a canvas with this name
            $canvas = $this->repository->findCanvasByName(CanvasName::create($canvasName));
            if ($canvas)
                throw new CanvasAlreadyExists();

            //Create canvas
            $canvas = Canvas::createEmpty(
                CanvasName::create($canvasName),
                CanvasWidth::create($canvasWidth),
                CanvasHeight::create($canvasHeight)
            );

            //Initialize spaceship position
            $canvas->initSpaceshipPosition();

            //Initialize obstacles on canvas
            if ($numObstacles > 0) {
                $canvas->initObstaclesAtRandomPositions($numObstacles);
            }

            //Save on database
            $this->repository->saveCanvas($canvas);

        } catch (Exception $error) {
            $this->logger->error($error);
            throw $error;
        }

        //Get domain events
        $domainEvents = $canvas->pullDomainEvents();

        //todo publish domain events

        //Return canvas DTO by Data Mapper
        return $this->dataMapper::toPrimitives($canvas);

    }

}
