<?php

namespace App\Enums;

enum InquiryStatus: string
{
    case Active = 'ACTIVE';
    case Processed = 'PROCESSED';
    case Failed = 'FAILED';
}