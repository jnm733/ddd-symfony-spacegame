<?php

namespace MyProject\Apps\SpaceGame\Api\Controller\Canvases;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use MyProject\SpaceGame\Modules\Canvases\Application\CanvasCreator;
use MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions\CanvasAlreadyExists;

class CanvasesCreateGetController {

    private CanvasCreator $useCase;

    public function __construct(CanvasCreator $useCase) {
        $this->useCase = $useCase;
    }

    public function __invoke(Request $request): Response {
        $canvasName = strtolower($request->get('name') ?? '');
        $canvasWidth = (int) ($request->get('width') ?? 0);
        $canvasHeight = (int) ($request->get('height') ?? 0);
        $numObstacles = (int) ($request->get('num_obstacles') ?? 0);

        $errors = [];
        if( !$canvasName )
            $errors[] = 'Missing request parameter "name".';

        if( !$canvasWidth || $canvasWidth < 0 )
            $errors[] = 'Missing or invalid value of request parameter "width".';

        if( !$canvasHeight || $canvasHeight < 0 )
            $errors[] = 'Missing or invalid value of request parameter "height".';

        if ($numObstacles > 0 && $numObstacles+1 > $canvasWidth*$canvasHeight)
            $errors[] = 'The value for "num_obstacles" must be lower than canvas size.';

        if( !empty($errors) ){
            return new JsonResponse(['errors' => $errors],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $canvasApiDTO = $this->useCase->run($canvasName, $canvasWidth, $canvasHeight, $numObstacles);
        } catch (CanvasAlreadyExists $error) {
            return new JsonResponse([
                'errors' => $error->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Exception $error) {
            return new JsonResponse([
                'errors' => 'An internal error has occurred. Please, try again',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'status' => 'created',
            'canvas' => $canvasApiDTO
        ], Response::HTTP_CREATED);
    }

}
