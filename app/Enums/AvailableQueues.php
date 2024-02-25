<?php

namespace App\Enums;

enum AvailableQueues: string
{
    case Dispatching = 'DISPATCHING';
    case Processing = 'PROCESSING';
}