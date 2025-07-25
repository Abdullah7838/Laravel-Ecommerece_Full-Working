<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Order;
use App\Models\Product;
use Faker\Core\Number;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Order Information')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Select::make('coupon_id')
                            ->label('Coupon')
                            ->relationship('coupon', 'code')
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $coupon = \App\Models\Coupon::find($state);
                                    $set('coupon_code', $coupon->code);
                                    
                                    // Calculate discount based on subtotal
                                    $subtotal = 0;
                                    if ($repeaters = $get('items')) {
                                        foreach ($repeaters as $key => $repeater) {
                                            $subtotal += $get("items.{$key}.total_amount");
                                        }
                                    }
                                    
                                    $discount = $coupon->calculateDiscount($subtotal);
                                    $set('discount_amount', $discount);
                                } else {
                                    $set('coupon_code', null);
                                    $set('discount_amount', 0);
                                }
                            }),
                            
                        TextInput::make('coupon_code')
                            ->label('Coupon Code')
                            ->disabled()
                            ->dehydrated(),
                            
                        TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix('PKR')
                            ->default(0),
                            
                        Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe',
                                'code' => 'Cash on Delivery',
                            ])
                            ->required(),

                        Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required(),

                        ToggleButtons::make('status')
                            ->inline()
                            ->default('new')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->colors([
                                'new' => 'info',
                                'processing' => 'warning',
                                'shipped' => 'success',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                            ])
                            ->icons([
                                'new' => 'heroicon-m-sparkles',
                                'processing' => 'heroicon-m-arrow-path',
                                'shipped' => 'heroicon-m-truck',
                                'delivered' => 'heroicon-m-check-badge',
                                'cancelled' => 'heroicon-m-x-circle',
                            ])
                            ->required(),

                        Select::make('currency')
                            ->options([
                                'pkr' => 'PKR',
                                'usd' => 'USD',
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                            ])
                            ->default('pkr')
                            ->required(),

                        Select::make('shipping_method')
                            ->options([
                                'fedex_ground' => 'FedEx Ground',
                                'fedex_express' => 'FedEx Express',
                                'fedex_overnight' => 'FedEx Overnight',
                                'ups_ground' => 'UPS Ground',
                                'ups_expedited' => 'UPS Expedited',
                                'ups_next_day_air' => 'UPS Next Day Air',
                                'dhl_ground' => 'DHL Ground',
                                'dhl_express' => 'DHL Express',
                                'usps_priority_mail' => 'USPS Priority Mail',
                                'usps_first_class_mail' => 'USPS First Class Mail',
                                'local_courier' => 'Local Courier',
                                'bike_messenger' => 'Bike Messenger',
                            ]),

                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->placeholder('Enter a Note...')
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->schema([

                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->columnSpan(4)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', optional(Product::find($state))->price ?? 0))

                                    ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', optional(Product::find($state))->price ?? 0)),

                                
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state*$get('unit_amount'))),
                                
                                TextInput::make('unit_amount')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(3),
                                
                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->dehydrated()
                                    ->columnSpan(3)

                            ])->columns(12),

                            Placeholder::make('subtotal_placeholder')
                                ->label('Subtotal')
                                ->content(function (Get $get, Set $set){
                                    $total = 0;
                                    if(!$repeaters = $get('items')){
                                        return $total;
                                    }

                                    foreach($repeaters as $key => $reapeater) {
                                        $total += $get("items.{$key}.total_amount");
                                    }

                                    $set('subtotal', $total);
                                    return \Illuminate\Support\Number::currency($total, 'PKR');
                                }),
                                
                            Hidden::make('subtotal')
                                ->default(0),
                                
                            Placeholder::make('discount_placeholder')
                                ->label('Discount')
                                ->content(function (Get $get){
                                    $discount = $get('discount_amount') ?? 0;
                                    return \Illuminate\Support\Number::currency($discount, 'PKR');
                                }),
                                
                            Placeholder::make('shipping_placeholder')
                                ->label('Shipping')
                                ->content(function (Get $get){
                                    $shipping = $get('shipping_amount') ?? 0;
                                    return \Illuminate\Support\Number::currency($shipping, 'PKR');
                                }),

                            Placeholder::make('grand_total_placeholder')
                                ->label('Grand Total')
                                ->content(function (Get $get, Set $set){
                                    $subtotal = $get('subtotal') ?? 0;
                                    $discount = $get('discount_amount') ?? 0;
                                    $shipping = $get('shipping_amount') ?? 0;
                                    
                                    $total = $subtotal - $discount + $shipping;
                                    $set('grand_total', $total);
                                    return \Illuminate\Support\Number::currency($total, 'PKR');
                                }),

                            Hidden::make('grand_total')
                                ->default(0)

                    ]),

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->numeric()
                    ->sortable()
                    ->money('PKR'),
                    
                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->numeric()
                    ->sortable()
                    ->money('PKR'),
                    
                TextColumn::make('grand_total')
                    ->label('Grand Total')
                    ->numeric()
                    ->sortable()
                    ->money('PKR'),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currency')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('shipping_method')
                    ->label('Shipping Method')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                //
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
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class
        ];
    }

    public static function getNavigationBadge() : ?string {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
