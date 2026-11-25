<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GuiaInteractiva extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Guía Operativa';

    protected static ?string $title = 'Guía de Operaciones CIAC TAMEP';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.guia-interactiva';

    public static function canAccess(): bool
    {
        return auth()->check();
    }
}
