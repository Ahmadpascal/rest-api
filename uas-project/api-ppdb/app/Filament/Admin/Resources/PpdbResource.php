<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PpdbResource\Pages;
// use App\Filament\Resources\PpdbResource\RelationManagers;
use App\Models\Ppdb;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\PpdbResource\RelationManagers\PpdbDocumentsRelationManager;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PpdbResource extends Resource
{
    protected static ?string $model = Ppdb::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function canViewAny(): bool
    // {
    //     return true;
    // }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Filament::auth()->user()?->id),

                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('nisn')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('asal_sekolah')
                    ->required(),

                Forms\Components\Select::make('jalur')
                    ->required()
                    ->options([
                        'beasiswa' => 'Beasiswa',
                        'prestasi' => 'Prestasi',
                        'umum'     => 'Umum',
                    ]),

                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'pending'  => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak'  => 'Ditolak',
                    ])
                    ->default('pending'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nisn')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jalur')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'diterima',
                        'danger'  => 'ditolak',
                    ])
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('terima')
                    ->label('Terima')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'diterima'])),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'ditolak'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PpdbDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPpdbs::route('/'),
            'create' => Pages\CreatePpdb::route('/create'),
            'edit' => Pages\EditPpdb::route('/{record}/edit'),
        ];
    }
}
