<?php declare( strict_types = 1 );

namespace MyProject\SpaceGame\Modules\Canvases\Domain\Exceptions;

use RuntimeException;

final class ObjectOutsideOfCanvas extends RuntimeException {

    public function __construct() {
        parent::__construct('The object cannot be outside the canvas');
    }

}
