<?php

declare(strict_types=1);
/**
 * ---.
 */

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Tenant\Filament\Resources\DomainResource;

class ListDomains extends ListRecords
{
    protected static string $resource = DomainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
