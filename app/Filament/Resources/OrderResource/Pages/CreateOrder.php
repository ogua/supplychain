<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Products;
use App\Models\Tax;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Forms\Components\Actions as ComponetAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Livewire;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;

class CreateOrder extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    
    protected static string $resource = OrderResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
         $record = $this->getRecord();

        return Notification::make()
            ->success()
            ->title('Order Created created')
            ->persistent()
            ->actions([
            ActionsAction::make('print')
            ->url(route('order-invoice', $record->id), shouldOpenInNewTab: true)
            ->button(),
        ])
        ->body('Order recorded successfully!.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $data["order_reff"] = blank($data["order_reff"]) ? "REF-".date('Ymd')."-".date('hms') : $data["order_reff"];
        
        return $data;
    }
    
    protected function getSteps() : array {
        return [
            
            Forms\Components\Wizard\Step::make('Name')
            ->description('Customer Information')
            ->schema([
                Forms\Components\Section::make('')
                ->description('')
                ->schema([
                    Forms\Components\TextInput::make('order_reff'),
                    
                    Forms\Components\Hidden::make('company_id')
                    ->default(null),
                    
                    Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->options(Customer::pluck('customer_name','id'))
                    ->preload()
                    ->searchable()
                    ->required(),
                    
                    Forms\Components\Select::make('status')
                    ->options(['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'])
                    ->searchable()
                    ->required(),
                    ])
                    ->columns(2),
                    
                ]),
                
                Forms\Components\Wizard\Step::make('Order')
                ->description('Order Items')
                ->schema([
                    
                    TableRepeater::make('items')
                    ->relationship()
                    ->label("")
                    ->live()
                    ->afterStateUpdated(function($state,Forms\Get $get, Forms\Set $set){
                        
                    })
                    ->reorderable(false)
                    //->showLabels()
                    // ->headers([
                    //     Header::make('Product'),
                    //     Header::make('Qty')->width('100px'),
                    //     Header::make('Price')->width('150px'),
                    //     Header::make('Sub total'),
                    // ])
                    ->colStyles(function(){
                        return [
'quantity' => 'width: 80px;',
'price' => 'width: 150px;',
                        ];
                    })
                    ->schema([
                        Forms\Components\Select::make('product_id')
                        ->label('Product')
                        ->options(Products::pluck('name','id'))
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function($set,$state){
                            
                            if($state == ""){
                                $set("price",0);
                            }
                            
                            $product = Products::where('id',$state)->first();
                            
                            if($product){
                                $set("price",$product->price);
                            }else{
                                $set("price",0);
                            }
                            
                            
                        })
                        ->searchable(),
                        
                        Forms\Components\TextInput::make('quantity')
                        ->label('Qty')
                        ->integer()
                        ->default(1)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Forms\Get $get,Forms\Set $set, $state,$livewire){
                            $tot = $state * $get('price');
                            $set("total",$tot);                           
                            
                        })
                        ->afterStateHydrated(function (Forms\Get $get,Forms\Set $set, $state,$livewire){
                            $tot = $state * $get('price');
                            $set("total",$tot);                           
                            
                        })
                        ->required(),
                        
                        Forms\Components\TextInput::make('price')
                        ->prefix("GHC")
                        ->readOnly()
                        ->required()
                        ->default(0),
                        
                        
                        Forms\Components\TextInput::make('total')
                        ->prefix("GHC")
                        ->readOnly()
                        ->default(0),
                        
                        
                        Forms\Components\Placeholder::make('ptotal')
                        ->content(function ($get,$set){
                            $tot = $get("quantity") * $get('price');
                            $set("total",$tot);
                            
                            return "";
                        })
                        ->label(''),
                        
                        
                        ])
                        ->reorderable()
                        //->cloneable()
                        ->collapsible()
                        ->defaultItems(1)
                        ->columnSpan('full'),
                        
                        
                        Forms\Components\Section::make('')
                        ->description('')
                        ->schema([
                            
                            Forms\Components\Placeholder::make('total_items_added')
                            ->label("")
                            ->content(function ($get,$set){
                                $items = collect($get("items"))->count();
                                
                                return "Total items added: ".$items;
                            }),
                            
                            Forms\Components\Placeholder::make('total_items_added')
                            ->label("")
                            ->content(function ($get,$set){
                                $totqty = collect($get("items"))
                                ->pluck('quantity')
                                ->sum();
                                
                                return "Total quantity added: ".$totqty;
                            }),
                            
                            Forms\Components\Placeholder::make('grand_total')
                            ->label("")
                            ->content(function ($get,$set){
                                $totitems = collect($get("items"))
                                ->pluck('total')
                                ->sum();
                                
                                $set("total",$totitems);
                                $set("grand_total",$totitems);
                                
                                return "Sub total: GHC".number_format($totitems,0);
                            }),
                            
                            ])
                            ->columns(3),
                            
                        ]),
                        
                        Forms\Components\Wizard\Step::make('Payment')
                        ->description('Payment options')
                        ->schema([
                            
                            Forms\Components\Section::make('')
                            ->schema([
                                
                                Forms\Components\Placeholder::make('pos_grand_total')
                                ->columnSpanFull()
                                ->extraAttributes([
                                    'class' => 'text-center bg-red-500 text-white text-12xl font-bold dark:bg-gray-500 p-2'
                                    ])
                                    ->content(function ($get,$set){
                                        self::updateTotals($get, $set);
                                        return "SubTotal: GHC ".number_format($get("total"),2);
                                    })
                                    ->label(''),
                                    
                                    Forms\Components\Hidden::make('tottax')
                                    ->dehydrated(false),
                                    
                                    
                                    ComponetAction::make([
                                        
                                        Action::make("Tax")
                                        ->icon('heroicon-m-pencil-square')
                                        ->label(fn ($get) =>  "Tax: GHC ".number_format($get("order_tax_rate"),2))
                                        ->modalSubmitActionLabel('Add Tax')
                                        ->form([
                                            Forms\Components\Select::make('m_order_tax')
                                            ->label('')
                                            ->options(Tax::pluck('name','id'))
                                            ->preload()
                                            ->searchable()
                                            ->required()
                                            
                                            ])
                                            ->action(function(array $data, Forms\Set $set, Forms\Get $get){
                                                //$set("order_tax", $data["discount_type"]);
                                                $tax = Tax::find($data["m_order_tax"]);
                                                
                                                $rate = $tax->rate;
                                                $set("order_tax",$tax->id);
                                                $set("order_tax_rate",$rate);
                                                
                                                self::updateTotals($get, $set);
                                            }),
                                            
                                            
                                            Action::make("Shippingcost")
                                            ->icon('heroicon-m-pencil-square')
                                            ->label(fn ($get) =>  "Shipping: GHC ".number_format($get("shipping_cost"),2))
                                            ->modalSubmitActionLabel('Add Shipping cost')
                                            ->form([
                                                
                                                Forms\Components\TextInput::make('setshipping')
                                                ->label('Shipping cost')
                                                ->numeric()
                                                ->default(0),
                                                
                                                ])
                                                ->action(function(array $data, Forms\Set $set, Forms\Get $get){
                                                    $set("shipping_cost",$data["setshipping"]);
                                                    self::updateTotals($get, $set);
                                                }),
                                                
                                                Action::make("Discount")
                                                ->icon('heroicon-m-pencil-square')
                                                ->size('sm')
                                                ->label(fn ($get) => "Discount: GHC ".number_format($get("order_discount_value"),2))
                                                ->modalSubmitActionLabel('Add Discount')
                                                ->form([
                                                    Forms\Components\Select::make('discount_type')
                                                    ->label('Discount Type')
                                                    ->options([
                                                        'Flat' => 'Flat',
                                                        'Discount' => 'Discount'
                                                        ])
                                                        ->searchable()
                                                        ->required(),
                                                        
                                                        Forms\Components\TextInput::make('value')
                                                        ->label('Value')
                                                        ->required(),
                                                        
                                                        ])
                                                        ->action(function(array $data, Forms\Set $set, Forms\Get $get){
                                                            $set("order_discount_type", $data["discount_type"]);
                                                            $set("order_discount_value", $data["value"]);
                                                            self::updateTotals($get, $set);
                                                        })
                                                        
                                                        
                                                        
                                                        ])
                                                        ->columnSpanFull()
                                                        ->fullWidth(),
                                                        
                                                        
                                                        Forms\Components\Placeholder::make('pos_grand_total')
                                                        ->columnSpanFull()
                                                        ->extraAttributes([
                                                            'class' => 'text-center bg-red-500 text-white text-12xl font-bold dark:bg-gray-500 p-2'
                                                            ])
                                                            ->content(function ($get,$set){
                                                                
                                                                return "Grand Total: GHC ".number_format($get("grand_total"),2);
                                                            })
                                                            ->label(''),
                                                            
                                                            
                                                            ])
                                                            ->columns(3),
                                                            
                                                            Forms\Components\Hidden::make('total')
                                                            ->default(0)
                                                            ->label('Sub total'),
                                                            
                                                            Forms\Components\Hidden::make('order_tax_rate')
                                                            ->default(null),
                                                            Forms\Components\Hidden::make('order_tax_value')
                                                            ->default(null),
                                                            Forms\Components\Hidden::make('order_tax')
                                                            ->default(null),
                                                            Forms\Components\Hidden::make('order_discount_type')
                                                            ->default(null),
                                                            Forms\Components\Hidden::make('order_discount_value')
                                                            ->default(null),
                                                            Forms\Components\Hidden::make('total_discount')
                                                            ->default(0),
                                                            Forms\Components\Hidden::make('shipping_cost')
                                                            ->default(0),
                                                            
                                                            Forms\Components\Hidden::make('grand_total')
                                                            ->default(0),
                                                            
                                                            Forms\Components\Hidden::make('paid_amount')
                                                            ->default(0),
                                                            
                                                            Forms\Components\Section::make('Payment')
                                                            ->description('Record Amount Paid')
                                                            ->relationship('payment')
                                                            ->schema([
                                                                
                                                                Forms\Components\DateTimePicker::make('paid_on')
                                                                ->label('paid on')
                                                                ->required(),
                                                                
                                                                Forms\Components\Select::make('paying_method')
                                                                ->options([
                                                                    'CASH' => 'CASH',
                                                                    'CHEQUE' => 'CHEQUE',
                                                                    'BANK TRANSFER' => 'BANK TRANSFER'
                                                                    ])
                                                                    ->searchable()
                                                                    ->live()
                                                                    ->required(),
                                                                    
                                                                    // `cash_register_id`, 
                                                                    // `account_id`, 
                                                                    // `customer_id`,
                                                                    
                                                                    //payments
                                                                    
                                                                    Forms\Components\TextInput::make('bankname')
                                                                    ->label("Bank name")
                                                                    ->visible(fn ($get): bool => $get("paying_method") == "BANK TRANSFER")
                                                                    ->required(),
                                                                    
                                                                    Forms\Components\TextInput::make('accountnumber')
                                                                    ->label("Account number")
                                                                    ->visible(fn ($get): bool => $get("paying_method") == "BANK TRANSFER")
                                                                    ->required(),
                                                                    
                                                                    Forms\Components\TextInput::make('cheque_no')
                                                                    ->label("Cheque number")
                                                                    ->visible(fn ($get): bool => $get("paying_method") == "CHEQUE")
                                                                    ->required(),
                                                                    
                                                                    
                                                                    Forms\Components\Hidden::make('user_id')
                                                                    ->default(auth()->user()->id),
                                                                    
                                                                    Forms\Components\TextInput::make('amount')
                                                                    ->label('Amount paid')
                                                                    ->default(0),
                                                                    
                                                                    Forms\Components\TextInput::make('change')
                                                                    ->label('Balance')
                                                                    ->default(0),
                                                                    
                                                                    Forms\Components\Hidden::make('customer_id'),
                                                                    
                                                                    Forms\Components\Textarea::make('payment_note')
                                                                    ->columnSpanFull(),
                                                                    
                                                                    ])->columns(4),
                                                                    
                                                                    
                                                                    
                                                                    
                                                                ]),
                                                            ];
                                                        }


    protected function afterCreate(): void
    {
        $records = $this->getRecord();

        $amount = $records->paid_amount;
        
        $payment = Payment::where('order_id',$records->id)->first();
        $payment->customer_id = $records->user_id;
        $payment->save();

        $totpaid = $payment->amount;
        $left = $totpaid - $amount;

        $records->paid_amount = $left;
        $records->save();
        
        //update quantity
        foreach (collect($records->items) as $row) {

            $product = Products::where('id', $row->product_id)->first();
            $qty = $row->quantity;
            $totalqty = $product->quantity - $qty;
            $product->quantity = $totalqty;
            $product->save();
        }

    }
                                                        
                                                        
                                                        // This function updates totals based on the selected products and quantities
                                                        public static function updateTotals(Forms\Get $get, Forms\Set $set): void
                                                        {                
                                                            // Retrieve all selected products and remove empty rows
                                                            $selectedProducts = collect($get('items'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));
                                                            
                                                            $payments = collect($get('Payment')) ?? [];
                                                            
                                                            $paymentsmade = [];
                                                            
                                                            foreach ($payments as $payment) {
                                                                $paymentsmade["amount"] = $payment['amount'];
                                                            }
                                                            
                                                            $totalpayment = $payments->reduce(function ($totalpayment, $payment) use ($paymentsmade) {
                                                                return $totalpayment + $paymentsmade[$payment['amount']];
                                                            }, 0);
                                                            
                                                            
                                                            $prices = [];
                                                            
                                                            foreach ($selectedProducts as $selectedProduct) {
                                                                
                                                                $tot = $selectedProduct["quantity"] * $selectedProduct['price'];
                                                                
                                                                $prices[$selectedProduct['product_id']] = $tot;
                                                                
                                                            }
                                                            
                                                            
                                                            // Calculate subtotal based on the selected products and quantities
                                                            $subtotal = $selectedProducts->reduce(function ($subtotal, $product) use ($prices) {
                                                                return $subtotal + $prices[$product['product_id']];
                                                            }, 0);
                                                            
                                                            //add tax
                                                            $tax = ($get("order_tax_rate") / 100 ) * $subtotal;
                                                            
                                                            $set("order_tax_value",$tax);
                                                            
                                                            //add shipping cost
                                                            $shipping = $get("shipping_cost");
                                                            
                                                            //substract discount
                                                            $order_discount_type = $get("order_discount_type");
                                                            if($order_discount_type == "Flat"){
                                                                $discount = $get("order_discount_value");
                                                            }elseif($order_discount_type == "Discount"){
                                                                $discount = ($get("order_discount_value") / 100) * $subtotal;
                                                            }else{
                                                                $discount = $get("order_discount_value");
                                                            }
                                                            
                                                            $set("total_discount",$discount);
                                                            
                                                            $grandtotal = ($subtotal + $tax + $shipping) -  ($discount);
                                                            
                                                            // Update the state with the new values
                                                            $set('total', number_format($subtotal, 2, '.', ''));
                                                            $set('grand_total', number_format($grandtotal, 2, '.', ''));
                                                            $set('payment.amount',number_format($grandtotal, 2, '.', ''));
                                                            
                                                            // $set('payment.grand_total', number_format($grandtotal, 2, '.', ''));
                                                            
                                                            //static::calculateitems($set,$get);
                                                        }         
                                                    }
                                                    