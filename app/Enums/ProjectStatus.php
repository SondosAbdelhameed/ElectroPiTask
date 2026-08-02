<?php

namespace App\Enums;

enum ProjectStatus : string
{
    case ACTIVE = 'Active';
    case COMPLETED = 'Completed';
    case Archived = 'Archived';
}
