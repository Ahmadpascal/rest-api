<?php

namespace App\Filament\Resources\PpdbResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\PpdbDocument;
use Illuminate\Validation\Rule;

class PpdbDocumentsRelationManager extends RelationManager
{
    // Nama relasi di model Ppdb
    protected static string $relationship = 'documents';

    // Attribute utama yang ditampilkan
    protected static ?string $recordTitleAttribute = 'file';

    protected function canCreate(): bool
    {
        return $this->getOwnerRecord()
            ->documents()
            ->count() < count(PpdbDocument::TYPES);
    }

    // Form untuk upload dokumen & set status
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                ->options([
                    'wajib' => 'Dokumen Wajib',
                    'tambahan' => 'Dokumen Tambahan',
                ])
                ->live()
                ->afterStateUpdated(fn (callable $get) => $get('category'))
                ->required(),
                
                Forms\Components\Select::make('type')
                    ->options(fn (callable $get) => match ($get('category')) {
                        'wajib' => [
                            'kk' => 'Kartu Keluarga',
                            'ijazah' => 'Ijazah',
                            'akta' => 'Akta',
                        ],
                        'tambahan' => [
                            'sertifikat' => 'Sertifikat',
                            'piagam' => 'Piagam Penghargaan',
                            'lainnya' => 'Dokumen Lainnya',
                        ],
                        default => [],
                    })
                    ->rules([
                        Rule::unique('documents', 'type')  // Tabel 'documents', kolom 'type'
                            ->where('ppdb_id', $this->getOwnerRecord()->id),  // Scope ke ppdb_id saat ini
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Dokumen')
                    ->disk('public')
                    ->directory('documents')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules([
                        'mimes:' . implode(',', PpdbDocument::ALLOWED_MIMES),
                        'max:' . PpdbDocument::MAX_SIZE,
                    ])
                    ->preserveFilenames()
                    ->openable()
                    ->downloadable()
                    ->previewable()
                    ->required(),  

                Forms\Components\Select::make('status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Terferifikasi',
                        'invalid' => 'Invalid',
                    ])
                    ->default('pending')
                    ->required(),
                
                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->rows(3)
                    ->placeholder('Catatan verifikasi (opsional)'),
            ]);
    }

    // Table untuk menampilkan dokumen
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('file')->label('Nama Dokumen'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'valid',
                        'danger'  => 'invalid',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Upload')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'invalid' => 'Invalid',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}