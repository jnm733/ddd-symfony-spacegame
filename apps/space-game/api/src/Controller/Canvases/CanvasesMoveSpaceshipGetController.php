<?php

namespace MyProject\Apps\SpaceGame\Api\Controller\Canvases;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use MyProject\SpaceGame\Modules\Canvases\Application\SpaceshipMover;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\CanvasNoExists;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\ObstacleOnMovePosition;

class CanvasesMoveSpaceshipGetController {

    private SpaceshipMover $useCase;

    public function __construct(SpaceshipMover $useCase) {
        $this->useCase = $useCase;
    }

    public function __invoke(string $canvasName, string $movementDirection): Response {

        $errors = [];
        if( !$canvasName )
            $errors[] = 'Missing value of endpoint parameter "canvasName".';

        if( !$movementDirection )
            $errors[] = 'Missing value of endpoint parameter "movementDirection".';

        if( !in_array($movementDirection, ['top', 'TOP', 'right', 'RIGHT', 'bottom', 'BOTTOM', 'left', 'LEFT']) )
            $errors[] = 'Invalid value for parameter "movementDirection"';

        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $canvasApiDTO = $this->useCase->run($canvasName, $movementDirection);
        } catch (CanvasNoExists $error) {
            return new JsonResponse([
                'errors' => $error->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (ObstacleOnMovePosition $error) {
            return new JsonResponse([
                'errors' => $error->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $error) {
            return new JsonResponse([
                'errors' => 'An internal error has occurred. Please, try again',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'status' => 'moved',
            'canvas' => $canvasApiDTO
        ]);
    }

}
