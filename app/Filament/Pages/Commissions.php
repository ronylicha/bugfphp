<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Dashboard as BaseDashboard;
use App\Livewire\DossiersClosed;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Commissions extends BaseDashboard
{
    use HasFiltersForm;
    protected static string $routePath = 'commissions';

    protected static ?int $navigationSort = 2;
    public static function getNavigationLabel(): string
    {
        return 'commissions';
    }




    public function getWidgets(): array
    {
        return [
            DossiersClosed::class
        ];
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make("user")
                            ->options(User::all()->pluck("name","id")),
                        DatePicker::make('startDate'),
                        DatePicker::make('endDate'),
                        // ...
                    ])
                    ->columns(3),
            ]);
    }

}
