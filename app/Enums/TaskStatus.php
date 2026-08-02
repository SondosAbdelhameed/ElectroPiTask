<?php

namespace App\Enums;

enum TaskStatus : string
{
    case TODO = 'Todo';
    case IN_PROGRESS = 'In Progress';
    case DONE = 'Done';
}
