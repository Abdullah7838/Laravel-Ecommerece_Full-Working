<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockProducts extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';
    
    protected function getTableHeading(): string
    {
        return 'Low Stock Products';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('in_stock', true)
                    ->whereNotNull('low_stock_threshold')
                    ->whereRaw('stock_quantity <= low_stock_threshold')
                    ->latest()
            )
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                    
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('category.name')
                    ->sortable(),
                    
                TextColumn::make('stock_quantity')
                    ->sortable()
                    ->label('Current Stock')
                    ->color('danger'),
                    
                TextColumn::make('low_stock_threshold')
                    ->sortable()
                    ->label('Threshold'),
                    
                IconColumn::make('in_stock')
                    ->boolean()
                    ->label('In Stock'),
                    
                TextColumn::make('price')
                    ->money('PKR')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->icon('heroicon-m-pencil-square')
            ])
            ->paginate(5);
    }
}