<?php

declare(strict_types=1);

namespace App\Domain\Data;

interface DomainEntityManagerInterface
{

    public function persist(object $entity): void;

    public function flush(): void;

    public function clear(): void;
}