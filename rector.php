<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/srcApp_KernelDevDebugContainer.xml')
    ->withSets([
        SymfonySetList::SYMFONY_34,
        // SymfonySetList::SYMFONY_CODE_QUALITY,
        // SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);