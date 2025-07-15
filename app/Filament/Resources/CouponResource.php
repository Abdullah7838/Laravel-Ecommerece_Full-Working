<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Coupon Information')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('type')
                            ->options([
                                'percentage' => 'Percentage',
                                'fixed' => 'Fixed Amount',
                            ])
                            ->required(),

                        TextInput::make('value')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->hint(fn (callable $get) => $get('type') === 'percentage' ? 'Value in percentage (%)' : 'Value in currency'),

                        TextInput::make('min_order_amount')
                            ->label('Minimum Order Amount')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('PKR'),

                        TextInput::make('max_uses')
                            ->label('Maximum Uses')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Unlimited'),

                        TextInput::make('used_count')
                            ->label('Used Count')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0),

                        DateTimePicker::make('starts_at')
                            ->label('Valid From')
                            ->placeholder('No start date'),

                        DateTimePicker::make('expires_at')
                            ->label('Valid Until')
                            ->placeholder('No expiration date'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'success',
                        'fixed' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('value')
                    ->formatStateUsing(function ($record) {
                        return $record->type === 'percentage' 
                            ? $record->value . '%' 
                            : 'PKR ' . number_format($record->value, 2);
                    }),

                TextColumn::make('min_order_amount')
                    ->label('Min. Order')
                    ->money('PKR')
                    ->placeholder('None'),

                TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(function ($record) {
                        return $record->max_uses 
                            ? $record->used_count . ' / ' . $record->max_uses
                            : $record->used_count . ' / ∞';
                    }),

                TextColumn::make('starts_at')
                    ->label('Valid From')
                    ->dateTime()
                    ->placeholder('No start date')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Valid Until')
                    ->dateTime()
                    ->placeholder('No expiration')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('is_active')
                    ->label('Active')
                    ->toggle(),

                Filter::make('has_started')
                    ->label('Has Started')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('starts_at', '<=', now())
                        ->orWhereNull('starts_at')),

                Filter::make('not_expired')
                    ->label('Not Expired')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('expires_at', '>=', now())
                        ->orWhereNull('expires_at')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}