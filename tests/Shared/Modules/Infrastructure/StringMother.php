<?php declare(strict_types=1);

namespace MyProject\Tests\Shared\Modules\Infrastructure;

final class StringMother {

    public static function random(int $length): string {
        return StringMother::generateRandom($length, $length);
    }

    public static function randomInRange(int $minLength, int $maxLength): string {
        return StringMother::generateRandom($minLength, $maxLength);
    }

    private static function generateRandom(int $min_length, int $max_length) {
        $length = rand($min_length, $max_length);
        $string = '';
        $vowels = array("a","e","i","o","u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );

        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }

        return $string;
    }

}