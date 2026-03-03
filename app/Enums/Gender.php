<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'M';
    case Female = 'F';

    public function label(): string
    {
        return match($this) {
            self::Male => 'Laki-laki',
            self::Female => 'Perempuan',
        };
    }

    public static function labels(): array
    {
        return array_reduce(self::cases(), function ($carry, $item) {
            $carry[$item->value] = $item->label();
            return $carry;
        }, []);
    }
}
