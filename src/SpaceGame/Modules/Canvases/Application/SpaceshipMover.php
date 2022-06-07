<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Application;

use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\CanvasNoExists;
use MyProject\SpaceGame\Modules\Canvases\Domain\Mappers\CanvasMapper;
use MyProject\SpaceGame\Modules\Canvases\Domain\Repositories\CanvasRepository;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\CanvasName;
use MyProject\SpaceGame\Modules\Canvases\Domain\ValueObjects\SpaceshipMovementDirection;

/**
 * Use Case to move canvases spaceship
 */
final class SpaceshipMover {

    private LoggerInterface $logger;
    private CanvasMapper $dataMapper;
    private CanvasRepository $repository;
    private static array $movementDirections = ['top', 'right', 'bottom', 'left'];

    public function __construct(
        LoggerInterface $logger,
        CanvasMapper $dataMapper,
        CanvasRepository $repository) {
        $this->logger = $logger;
        $this->dataMapper = $dataMapper;
        $this->repository = $repository;
    }

    public function run(
        string $canvasName,
        string $movementDirection): array {

        try {

            //Get movement direction
            $spaceShipMovement = new SpaceshipMovementDirection($movementDirection, SpaceshipMover::$movementDirections);

            //Check if exists this canvas
            $canvas = $this->repository->findCanvasByName(CanvasName::create($canvasName));
            if (!$canvas)
                throw new CanvasNoExists();

            //Do movement by direction value
            switch ($spaceShipMovement->value()) {
                case 'top':
                  $canvas->moveToUpSpaceship();
                  break;

                case 'right':
                    $canvas->moveToRightSpaceship();
                    break;

                case 'bottom':
                    $canvas->moveToDownSpaceship();
                    break;

                case 'left':
                    $canvas->moveToLeftSpaceship();
                    break;

                default:
                    throw new RuntimeException('Unrecognized movement direction');
            }

            //Save state on database
            $this->repository->saveCanvas($canvas);

            //Return canvas DTO by Data Mapper
            return $this->dataMapper::toPrimitives($canvas);

        } catch (Exception $error) {
            $this->logger->error($error);
            throw $error;
        }

    }

}
