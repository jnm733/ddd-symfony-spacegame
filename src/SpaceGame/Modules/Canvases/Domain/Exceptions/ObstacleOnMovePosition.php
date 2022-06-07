<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions;

use RuntimeException;

final class ObstacleOnMovePosition extends RuntimeException {

    public function __construct() {
        parent::__construct('Can not move. There is an obstacle in the new position');
    }

}
