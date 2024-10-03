<?php

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Tenant\Filament\Resources\DomainResource;

class CreateDomain extends CreateRecord
{
    protected static string $resource = DomainResource::class;
}
