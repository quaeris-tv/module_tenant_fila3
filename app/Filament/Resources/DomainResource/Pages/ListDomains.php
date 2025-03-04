<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources\DomainResource\Pages;

use Filament\Tables;
use Modules\Tenant\Filament\Resources\DomainResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListDomains extends XotBaseListRecords
{
    protected static string $resource = DomainResource::class;

    public function getListTableColumns(): array
    {
        return [
            'id' => Tables\Columns\TextColumn::make('id')
                ->numeric()
                ->sortable()
                ->searchable(),
            'domain' => Tables\Columns\TextColumn::make('domain')
                ->sortable()
                ->searchable(),
            'tenant_id' => Tables\Columns\TextColumn::make('tenant_id')
                ->numeric()
                ->sortable()
                ->searchable(),
            'created_at' => Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}

// public static function table(Table $table): Table
// {
//     return $table
//         ->columns([
//             TextColumn::make('name')
//                 ->searchable()
//                 ->sortable()
//                 ->weight('medium')
//                 ->alignLeft(),
//         ]);
// }

// public static function tableOld(Table $table): Table
// {
//     return $table
//         ->columns([
//             // thumbnail
//             ImageColumn::make('thumbnail')
//
//                 ->rounded(),

//             // title
//             TextColumn::make('title')
//                 ->searchable()
//                 ->sortable()
//                 ->weight('medium')
//                 ->alignLeft(),

//             // brand
//             TextColumn::make('brand')
//                 ->searchable()
//                 ->sortable()
//                 ->color('gray')
//                 ->alignLeft(),

//             // category
//             TextColumn::make('category')
//                 ->sortable()
//                 ->searchable(),

//             // description
//             TextColumn::make('description')
//                 ->sortable()
//                 ->searchable()
//                 ->limit(30),

//             // price
//             BadgeColumn::make('price')
//                 ->colors(['secondary'])
//                 ->prefix('$')
//                 ->sortable()
//                 ->searchable(),

//             // rating
//             BadgeColumn::make('rating')
//                 ->colors([
//                     'danger' => static fn ($state): bool => $state <= 3,
//                     'warning' => static fn ($state): bool => $state > 3 && $state <= 4.5,
//                     'success' => static fn ($state): bool => $state > 4.5,
//                 ])
//                 ->sortable()
//                 ->searchable(),
//         ])
//         ->filters([
//             // brand
//             SelectFilter::make('brand')
//                 ->multiple()
//                 ->options(Domain::select('brand')
//                     ->distinct()
//                     ->get()
//                     ->pluck('brand', 'brand')
//                 ),

//             // category
//             SelectFilter::make('category')
//                 ->multiple()
//                 ->options(Domain::select('category')
//                     ->distinct()
//                     ->get()
//                     ->pluck('category', 'category')
//                 ),
//         ])
//         ->actions([
//             Tables\Actions\EditAction::make(),
//         ])
//         ->bulkActions([
//             Tables\Actions\BulkActionGroup::make([
//                 Tables\Actions\DeleteBulkAction::make(),
//             ]),
//         ])
//         ->emptyStateActions([
//         ]);
// }
