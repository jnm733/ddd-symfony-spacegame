<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions;

use RuntimeException;

final class SpaceshipNotInitialized extends RuntimeException {

    public function __construct() {
        parent::__construct('The spaceship is not initialized');
    }

}
