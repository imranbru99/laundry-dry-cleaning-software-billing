<?php

declare(strict_types=1);

namespace App\Filament\Business\Resources;

use App\Models\Claim;
use App\Support\Modules\ModuleManager;
use App\Support\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Support';

    public static function shouldRegisterNavigation(): bool
    {
        $tenant = TenantContext::tenant() ?? auth()->user()?->tenant;
        if ($tenant === null) {
            return false;
        }

        return app(ModuleManager::class)->isEnabled('support', $tenant);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Business\Resources\ClaimResource\Pages\ListClaims::route('/'),
            'create' => \App\Filament\Business\Resources\ClaimResource\Pages\CreateClaim::route('/create'),
            'edit' => \App\Filament\Business\Resources\ClaimResource\Pages\EditClaim::route('/{record}/edit'),
        ];
    }
}
