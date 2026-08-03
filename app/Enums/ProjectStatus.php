<?php

namespace App\Enums;

enum ProjectStatus : string
{
    case ACTIVE = 'Active';
    case COMPLETED = 'Completed';
    case Archived = 'Archived';

    public static function values(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
