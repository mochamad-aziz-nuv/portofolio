<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PondResource\Pages;
use App\Models\Pond;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PondResource extends Resource
{
    protected static ?string $model = Pond::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Manajemen Tambak';
    protected static ?string $modelLabel = 'Kolam';
    protected static ?string $pluralModelLabel = 'Daftar Kolam';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar Kolam')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kolam')
                            ->placeholder('contoh: Kolam A1 (Intensif)')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kolam')
                            ->placeholder('PL-A1')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        Forms\Components\Select::make('pond_type')
                            ->label('Tipe Konstruksi')
                            ->options([
                                'hdpe' => 'HDPE Liners',
                                'concrete' => 'Beton (Semen)',
                                'earthen' => 'Tanah / Tradisional',
                            ])
                            ->default('hdpe')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status Operasional')
                            ->options([
                                'active' => 'Aktif Budidaya',
                                'preparation' => 'Persiapan / Pengisian Air',
                                'maintenance' => 'Perawatan / Pengeringan',
                                'harvested' => 'Baru Panen',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->label('Lokasi Blok')
                            ->placeholder('Blok Utara No. 01'),
                    ])->columns(2),

                Forms\Components\Section::make('Dimensi & Kapasitas Air')
                    ->schema([
                        Forms\Components\TextInput::make('length_m')
                            ->label('Panjang (m)')
                            ->numeric()
                            ->suffix('m'),
                        Forms\Components\TextInput::make('width_m')
                            ->label('Lebar (m)')
                            ->numeric()
                            ->suffix('m'),
                        Forms\Components\TextInput::make('depth_m')
                            ->label('Kedalaman Air (m)')
                            ->numeric()
                            ->default(1.20)
                            ->suffix('m')
                            ->required(),
                        Forms\Components\TextInput::make('area_sqm')
                            ->label('Luas Kolam (m²)')
                            ->numeric()
                            ->suffix('m²')
                            ->required(),
                        Forms\Components\TextInput::make('volume_m3')
                            ->label('Estimasi Volume Air (m³)')
                            ->numeric()
                            ->suffix('m³'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kolam')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Pond $record) => $record->location ?? 'Lokasi umum'),
                Tables\Columns\TextColumn::make('pond_type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'hdpe' => 'HDPE Liners',
                        'concrete' => 'Beton',
                        'earthen' => 'Tanah',
                        default => $state,
                    })
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('area_sqm')
                    ->label('Luas')
                    ->suffix(' m²')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_m3')
                    ->label('Volume')
                    ->suffix(' m³')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'preparation' => 'warning',
                        'maintenance' => 'gray',
                        'harvested' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Aktif',
                        'preparation' => 'Persiapan',
                        'maintenance' => 'Perawatan',
                        'harvested' => 'Selesai Panen',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'active' => 'Aktif',
                        'preparation' => 'Persiapan',
                        'maintenance' => 'Perawatan',
                        'harvested' => 'Selesai Panen',
                    ]),
                Tables\Filters\SelectFilter::make('pond_type')
                    ->label('Filter Tipe')
                    ->options([
                        'hdpe' => 'HDPE',
                        'concrete' => 'Beton',
                        'earthen' => 'Tanah',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPonds::route('/'),
            'create' => Pages\CreatePond::route('/create'),
            'view' => Pages\ViewPond::route('/{record}'),
            'edit' => Pages\EditPond::route('/{record}/edit'),
        ];
    }
}
