<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Tenant\Filament\Resources\DomainResource\Pages;
use Modules\Tenant\Models\Domain;

class DomainResource extends \Modules\Xot\Filament\Resources\XotBaseResource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getFormSchema(): array
    {
        return [
            // title
            TextInput::make('title'),

            // brand
            TextInput::make('brand'),

            // category
            TextInput::make('category'),

            // description
            RichEditor::make('description'),

            // price
            TextInput::make('price')
                ->prefix('$'),

            // rating
            TextInput::make('rating')
                ->numeric(),
        ];
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

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
