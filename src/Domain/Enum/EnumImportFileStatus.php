<?php

namespace App\Domain\Enum;

enum EnumImportFileStatus: string
{
    case SAVED = 'saved';
    case DONE = 'done';
    case ERROR = 'error';
}