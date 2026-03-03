<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case Single = 'single';
    case Married = 'married';
    case Divorced = 'divorced';

    public function label(): string
    {
        return match($this) {
            self::Single => 'Belum Menikah',
            self::Married => 'Menikah',
            self::Divorced => 'Cerai',
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
