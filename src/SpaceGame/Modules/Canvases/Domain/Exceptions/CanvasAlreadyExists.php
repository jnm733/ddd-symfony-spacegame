<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions;

use RuntimeException;

final class CanvasAlreadyExists extends RuntimeException {

    public function __construct() {
        parent::__construct('A canvas with this name already exists');
    }

}
