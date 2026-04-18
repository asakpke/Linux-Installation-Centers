<?php

namespace App\Enums;

enum ReportCategory: string
{
    case USER_CONDUCT = 'user_conduct';
    case SPAM_INSTALL_REQUEST = 'spam_install_request';
    case HARASSMENT = 'harassment';
    case OTHER = 'other';
}
