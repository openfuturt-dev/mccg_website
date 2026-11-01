<?php

namespace App\Filament\Resources\PageSeos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageSeoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page')->schema([
                    TextInput::make('page_name')->label('Nom de la page')->required(),
                    TextInput::make('slug')->label('Identifiant / URL')->required()->unique(ignoreRecord: true),
                ])->columns(2)->columnSpanFull(),
                Section::make('Référencement')->schema([
                    TextInput::make('meta_title')->label('Meta title')->required()->maxLength(60)->helperText('Idéalement entre 50 et 60 caractères.'),
                    Textarea::make('meta_description')->label('Meta description')->required()->maxLength(160)->rows(3),
                    Textarea::make('keywords')->label('Mots-clés')->rows(3)->helperText('Séparez les mots-clés par des virgules.')->columnSpanFull(),
                    FileUpload::make('og_image')->label('Image Open Graph')->image()->directory('seo')->disk('public')->imageEditor()->columnSpanFull(),
                ])->columns(2)->columnSpanFull(),
            ]);
    }
}
