<?php

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Tenant\Filament\Resources\DomainResource;

class EditDomain extends EditRecord
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
