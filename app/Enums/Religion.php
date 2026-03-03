<?php

namespace App\Enums;

enum Religion: string
{
    case Islam = 'islam';
    case ChristianityProtestant = 'christianity_protestant';
    case Catholic = 'catholic';
    case Hindu = 'hindu';
    case Buddhism = 'buddhism';
    case Confucianism = 'confucianism';

    public function label(): string
    {
        return match($this) {
            self::Islam => 'Islam',
            self::ChristianityProtestant => 'Kristen Protestan',
            self::Catholic => 'Katolik',
            self::Hindu => 'Hindu',
            self::Buddhism => 'Buddha',
            self::Confucianism => 'Khonghucu',
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
