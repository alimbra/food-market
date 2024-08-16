<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum EnumImportTransactionType: string
{
    case SUCCESS_IMPORT = 'success_import';
    case FAILURE_IMPORT = 'failure_import';
}