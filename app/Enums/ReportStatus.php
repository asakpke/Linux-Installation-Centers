<?php

namespace App\Enums;

enum ReportStatus: string
{
    case OPEN = 'open';
    case DISMISSED = 'dismissed';
    case ACTIONED = 'actioned';
}
