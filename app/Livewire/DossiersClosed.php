<?php

namespace App\Livewire;

use App\Models\Product;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\MaxWidth;

class DossiersClosed extends BaseWidget
{
    use InteractsWithPageFilters;

    public $product_com_value;
    public $product_com_type;

    protected int|string|array $columnSpan = 'full';

    public function getTableModelLabel(): ?string
    {
        return "commission";
    }

    public function getTablePluralModelLabel(): ?string
    {
        return "commissions";
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    public function getQuery()
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $user = $this->filters['user'] ?? null;
        if (is_null($user) OR empty($user)) {
            $user = Auth::id();
        }

        if (is_null($startDate) or is_null($endDate)) {

            $startDate = Carbon::now()->startOfMonth()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } else {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        $query = Product::query();
        if (!is_null($user)) {
            $query->where('user_id', $user);
        }
        $query->orWhere(function (Builder $query) use ($startDate, $endDate) {
            $query->where(
                [
                    ["com_cancel", 1],
                ]
            )
                ->whereBetween('date_com_cancel', [$startDate, $endDate]);
        });

        return $query;
    }



    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->getQuery()
            )
            ->heading(__("commissions"))
            ->columns([
                TextColumn::make("created_at")
                    ->dateTime("d/m/Y H:i"),
                TextColumn::make("user.name"),
                ColumnGroup::make("selled products", [
                    TextColumn::make("name"),
                    TextColumn::make("qte"),
                    TextColumn::make("value")
                        ->suffix("€"),
                ]),
                ColumnGroup::make("commissions", [
                    TextColumn::make("com_to_pay")
                        ->color('success')
                        ->placeholder("0,00")
                        ->suffix('€')
                        ->summarize(Sum::make()
                            ->query(fn(QueryBuilder $query) => $query->where([
                                ['com_payed', "=", 0],
                                ['com_cancel', "=", 0]
                            ]))
                            ->label(__("total commission")),
                        ),
                    IconColumn::make("com_payed")
                        ->translateLabel()
                        ->icon(fn(string $state): string => match ($state) {
                            '1' => 'heroicon-c-check-circle',
                            '0' => 'heroicon-c-x-circle',
                            default => 'heroicon-c-question-mark-circle',
                        })
                        ->color(fn(string $state): string => match ($state) {
                            '0' => 'danger',
                            '1' => 'success',
                            default => 'gray',
                        }),
                    IconColumn::make("com_cancel")
                        ->translateLabel()
                        ->icon(fn(string $state): string => match ($state) {
                            '1' => 'heroicon-c-check-circle',
                            '0' => 'heroicon-c-x-circle',
                            default => 'heroicon-c-question-mark-circle',
                        })
                        ->color(fn(string $state): string => match ($state) {
                            '0' => 'danger',
                            '1' => 'success',
                            default => 'gray',
                        }),
                    TextColumn::make("com_to_cancel")
                        ->label(__("to_take"))
                        ->color('danger')
                        ->placeholder("0,00")
                        ->suffix('€')
                        ->summarize(Sum::make()
                            ->query(fn(QueryBuilder $query) => $query->where([
                                ['com_payed', "=", 1],
                                ['com_cancel', "=", 1]
                            ]))
                            ->label(__("total to take"))
                        ),
                ])

            ])
            ->groups([
                Group::make('user.name')
                    ->titlePrefixedWithLabel(false)
                    ->label(__("User")),
            ])
            ->actions([
                // Todo
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make("mark_payed")
                        ->translateLabel()
                        ->icon("heroicon-c-check-circle")
                        ->iconPosition(IconPosition::Before)
                        ->iconSize(IconSize::Small)
                        ->color("success")
                        ->requiresConfirmation()
                        ->modalHeading(__("mark as payed"))
                        ->modalDescription(__("sure to mark payed?"))
                        ->modalSubmitActionLabel(__("Yes, mark it payed"))
                        ->action(function(Collection $records,array $data, HasTable $livewire, BulkAction $action){
                            $now = Carbon::now();
                            foreach ($records as $record){
                                $record->update([
                                    'com_payed' => 1,
                                    "date_com_payed" => $now
                                ]);
                                $record->save();
                                $livewire->getTableRecords();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make("unmark_payed")
                        ->translateLabel()
                        ->icon("heroicon-c-x-circle")
                        ->iconPosition(IconPosition::Before)
                        ->iconSize(IconSize::Small)
                        ->color("danger")
                        ->requiresConfirmation()
                        ->modalHeading(__("unmark as payed"))
                        ->modalDescription(__("sure to unmark payed?"))
                        ->modalSubmitActionLabel(__("Yes, unmark it payed"))
                        ->action(function(Collection $records,array $data, HasTable $livewire, BulkAction $action){
                            foreach ($records as $record){
                                $record->update([
                                    'com_payed' => 0,
                                    "date_com_payed" => null
                                ]);
                                $record->save();
                                $livewire->getTableRecords();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ])
            ]);
    }


}
