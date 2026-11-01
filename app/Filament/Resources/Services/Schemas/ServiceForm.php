<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service')->schema([
                    TextInput::make('title')->label('Titre')->required()->live(onBlur: true)->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                    TextInput::make('slug')->label('URL')->required()->unique(ignoreRecord: true),
                    Textarea::make('short_description')->label('Description courte')->required()->rows(3)->columnSpanFull(),
                    RichEditor::make('content')->label('Contenu détaillé')->required()->columnSpanFull(),
                    TextInput::make('icon')->label('Icône')->helperText('Nom indicatif : calculator, scale, users…'),
                    Toggle::make('is_active')->label('Visible sur le site')->default(true),
                ])->columns(2)->columnSpanFull(),
                Section::make('Référencement SEO')->schema([
                    TextInput::make('meta_title')->label('Meta title')->maxLength(60),
                    Textarea::make('meta_description')->label('Meta description')->maxLength(160)->rows(3),
                    Textarea::make('keywords')->label('Mots-clés')->rows(3)->helperText('Séparez les mots-clés par des virgules.')->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
