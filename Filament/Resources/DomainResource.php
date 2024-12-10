<?php

declare(strict_types=1);

namespace Modules\Tenant\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Modules\Tenant\Models\Domain;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Tenant\Filament\Resources\DomainResource\Pages;

class DomainResource extends XotBaseResource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('brand'),
                TextInput::make('category'),
                RichEditor::make('description'),
                TextInput::make('price')
                    ->prefix('$'),
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
