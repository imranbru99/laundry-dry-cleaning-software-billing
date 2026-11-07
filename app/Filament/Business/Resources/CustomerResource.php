<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Filament\Business\Resources\CustomerResource\Pages\CreateCustomer;
use App\Filament\Business\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Business\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'CRM';

    public static function shouldRegisterNavigation(): bool
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;

        return $tenant !== null && app(ModuleManager::class)->isEnabled('crm', $tenant);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('type')->options([
                'individual' => 'Individual',
                'corporate' => 'Corporate',
                'household' => 'Household',
            ])->required()->default('individual'),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('email')->email(),
            Forms\Components\TextInput::make('phone')->tel(),
            Forms\Components\TextInput::make('company_name'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
            Forms\Components\Toggle::make('is_blacklisted'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\IconColumn::make('is_blacklisted')->boolean(),
            ])
            ->filters([])
            ->recordActions([
                Tables\Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
