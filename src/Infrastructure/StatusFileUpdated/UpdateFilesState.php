<?php

declare(strict_types=1);

namespace App\Infrastructure\StatusFileUpdated;

use App\Domain\Data\DomainEntityManagerInterface;
use App\Domain\Entity\ImportFile;
use App\Domain\Enum\EnumImportTransactionType;
use App\Domain\StatusFileUpdated\UpdateFileStateInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class UpdateFilesState implements UpdateFileStateInterface
{
    public function __construct(
        protected WorkflowInterface $importFileWorkflow,
        protected DomainEntityManagerInterface $entityManager
    ) {
    }

    public function successImport(ImportFile $importFile): void
    {
        if (!$this->importFileWorkflow->can($importFile, EnumImportTransactionType::SUCCESS_IMPORT->value)) {
            return;
        }

        $this->importFileWorkflow->apply($importFile, EnumImportTransactionType::SUCCESS_IMPORT->value);
        $this->entityManager->flush();
    }

    public function failureImport(ImportFile $importFile): void
    {
        if (!$this->importFileWorkflow->can($importFile, EnumImportTransactionType::FAILURE_IMPORT->value)) {
            return;
        }

        $this->importFileWorkflow->apply($importFile, EnumImportTransactionType::FAILURE_IMPORT->value);

        $this->entityManager->flush();
    }
}
