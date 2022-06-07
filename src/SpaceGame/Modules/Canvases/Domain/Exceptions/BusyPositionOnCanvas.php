<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions;

use RuntimeException;

final class BusyPositionOnCanvas extends RuntimeException {

    public function __construct() {
        parent::__construct('This position on canvas is busy');
    }

}
