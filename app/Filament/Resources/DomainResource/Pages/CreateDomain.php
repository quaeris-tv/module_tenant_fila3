<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Tenant\Filament\Resources\DomainResource;

class CreateDomain extends \Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord
{
    protected static string $resource = DomainResource::class;
}
