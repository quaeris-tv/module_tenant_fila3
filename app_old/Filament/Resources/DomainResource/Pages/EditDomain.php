<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Modules\Tenant\Filament\Resources\DomainResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditDomain extends \Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
{
    protected static string $resource = DomainResource::class;
}
