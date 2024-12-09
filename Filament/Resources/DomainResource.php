<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Modules\Tenant\Filament\Resources\DomainResource\Pages;
use Modules\Tenant\Models\Domain;

class DomainResource extends \Modules\Xot\Filament\Resources\XotBaseResource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

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
