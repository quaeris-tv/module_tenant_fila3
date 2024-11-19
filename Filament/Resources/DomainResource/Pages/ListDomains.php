<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Modules\Tenant\Filament\Resources\DomainResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;

class ListDomains extends XotBaseListRecords
{
    protected static string $resource = DomainResource::class;

    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->weight('medium')
                ->alignLeft(),
        ];
    }
}
