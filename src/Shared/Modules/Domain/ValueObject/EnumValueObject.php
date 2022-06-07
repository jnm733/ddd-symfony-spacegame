<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

abstract class EnumValueObject {

    private $value;
    private array $validValues;

    public function __construct($value, array $validValues) {
        $this->value = $value;
        $this->validValues = $validValues;
        $this->checkValueIsValid($value);
    }

    protected abstract function throwErrorForInvalidValue();

    protected function checkValueIsValid($value): void {
        if (!in_array($value, $this->validValues)) {
            $this->throwErrorForInvalidValue();
        }
    }

    public function value() {
        return $this->value;
    }

}