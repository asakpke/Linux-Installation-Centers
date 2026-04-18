<?php

namespace App\Enums;

enum InstallRequestStatus: string
{
    case OPEN = 'open';
    case MATCHED = 'matched';
    case CLOSED = 'closed';
    case CANCELLED = 'cancelled';
    case SPAM = 'spam';
}
