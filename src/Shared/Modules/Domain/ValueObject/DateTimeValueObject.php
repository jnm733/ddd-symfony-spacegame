<?php declare( strict_types = 1 );

namespace MyProject\Shared\Modules\Domain\ValueObject;

use Carbon\Carbon;

class DateTimeValueObject
{
    protected Carbon $value;

    protected function __construct(Carbon $value) {
        $this->value = $value;
    }

    public static function create(int $year, int $month=null, int $day=null, int $hour=null, int $minute=null, int $second=null): self {
        return new self(Carbon::create($year, $month, $day, $hour, $minute, $second));
    }

    public static function createFromUnix(int $unix): self {
        return new self(Carbon::createFromTimestamp($unix));
    }

    public static function createFromMilliseconds(int $millis): self {
        return new self(Carbon::createFromTimestampMs($millis));
    }

    public static function createFromFormat(string $time, string $format): self {
        return new self(Carbon::createFromFormat($format, $time));
    }

    public static function now(): self {
        return new self(Carbon::now());
    }

    public function toUnix(): int {
        return $this->value->timestamp;
    }

    public function toTimestamp(): int {
        return intval($this->value->valueOf());
    }

    public function toFormat(string $format): string {
        return $this->value->format($format);
    }

}
