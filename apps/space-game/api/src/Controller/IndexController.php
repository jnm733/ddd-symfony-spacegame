<?php

namespace MyProject\Apps\SpaceGame\Api\Controller;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class IndexController
{
    private CacheInterface $cache;

    public function __construct()
    {
        $this->cache = new FilesystemAdapter();
    }

    public function getCreateCanvas(Request $request): Response
    {
        $canvasName = strtolower($request->get('name') ?? '');
        $canvasWidth = (int) ($request->get('width') ?? 0);
        $canvasHeight = (int) ($request->get('height') ?? 0);

        $errors = [];
        if( !$canvasName ){
            $errors[] = 'Missing request parameter "name".';
        }

        if( !$canvasWidth ){
            $errors[] = 'Missing or invalid value of request parameter "width".';
        }

        if( !$canvasHeight ){
            $errors[] = 'Missing or invalid value of request parameter "height".';
        }

        if( !empty($errors) ){
            return new JsonResponse(
                [
                    'errors' => $errors
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->cache->delete('canvas_' . $canvasName);
        $canvas = $this->cache->get('canvas_' . $canvasName, function(ItemInterface $item) use (
            $canvasName,
            $canvasWidth,
            $canvasHeight
        ) {
            return [
                'name' => $canvasName,
                'width' => $canvasWidth,
                'height' => $canvasHeight,
                'spaceship' => [
                    'x' => 0,
                    'y' => 0,
                ]
            ];
        });

        return new JsonResponse([
            'status' => 'created',
            'canvas' => $canvas
        ], Response::HTTP_CREATED);
    }

    public function getMove($canvasName, $movementDirection): Response
    {
        if( !$canvasName ){
            return new JsonResponse(
                [
                    'errors' => [
                        'Missing value of endpoint parameter "canvasName".'
                    ]
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        if( !$movementDirection ){
            return new JsonResponse(
                [
                    'errors' => [
                        'Missing value of endpoint parameter "movementDirection".'
                    ]
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        if( !$this->cache->hasItem('canvas_' . $canvasName) ){
            return new JsonResponse(
                [
                    'errors' => [
                        'Missing canvas "' . $canvasName . '".'
                    ]
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $canvas = $this->cache->getItem('canvas_' . $canvasName)->get();
        $spaceship = $canvas['spaceship'];

        switch ($movementDirection){
            case 'top':
            case 'TOP': {
                --$spaceship['y'];
                if( $spaceship['y'] < 0 ){
                    $spaceship['y'] = ($canvas['height'] - 1);
                }
                break;
            }
            case 'right':
            case 'RIGHT': {
                ++$spaceship['x'];
                if( $spaceship['x'] > ($canvas['width'] - 1) ){
                    $spaceship['x'] = 0;
                }
                break;
            }
            case 'bottom':
            case 'BOTTOM': {
                ++$spaceship['y'];
                if( $spaceship['y'] > ($canvas['height'] - 1) ){
                    $spaceship['y'] = 0;
                }
                break;
            }
            case 'left':
            case 'LEFT': {
                --$spaceship['x'];
                if( $spaceship['x'] < 0 ){
                    $spaceship['x'] = ($canvas['width'] - 1);
                }
                break;
            }
            default: {
                return new JsonResponse(
                    [
                        'errors' => [
                            'Invalid movement direction "' . $movementDirection . '".'
                        ]
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }

        $this->cache->delete('canvas_' . $canvasName);
        $savedCanvas = $this->cache->get('canvas_' . $canvasName, function(ItemInterface $item) use ($canvas, $spaceship) {
            return [
                'name' => $canvas['name'],
                'width' => $canvas['width'],
                'height' => $canvas['height'],
                'spaceship' => $spaceship
            ];
        });

        return new JsonResponse([
            'status' => 'moved',
            'canvas' => $savedCanvas
        ]);
    }
}
