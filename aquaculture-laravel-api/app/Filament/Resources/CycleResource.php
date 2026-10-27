<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CycleResource\Pages;
use App\Models\Cycle;
use App\Models\Pond;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CycleResource extends Resource
{
    protected static ?string $model = Cycle::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Manajemen Tambak';
    protected static ?string $modelLabel = 'Siklus';
    protected static ?string $pluralModelLabel = 'Siklus Budidaya';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Siklus')
                    ->schema([
                        Forms\Components\Select::make('pond_id')
                            ->label('Kolam')
                            ->relationship('pond', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('cycle_code')
                            ->label('Kode Siklus')
                            ->placeholder('CYC-2024-VNM-A1')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('species')
                            ->label('Komoditas / Spesies')
                            ->default('Udang Vaname (Litopenaeus vannamei)')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status Siklus')
                            ->options([
                                'active' => 'Sedang Berjalan (Active)',
                                'completed' => 'Selesai Panen (Completed)',
                                'cancelled' => 'Dibatalkan (Cancelled)',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Target & Populasi')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Tebar Benih')
                            ->default(now())
                            ->required(),
                        Forms\Components\DatePicker::make('target_harvest_date')
                            ->label('Target Tanggal Panen'),
                        Forms\Components\DatePicker::make('actual_harvest_date')
                            ->label('Tanggal Realisasi Panen'),
                        Forms\Components\TextInput::make('initial_population')
                            ->label('Populasi Awal Tebar (ekor)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('target_fcr')
                            ->label('Target FCR')
                            ->numeric()
                            ->default(1.20)
                            ->step(0.01)
                            ->required(),
                        Forms\Components\TextInput::make('target_size')
                            ->label('Target Size (ekor/kg)')
                            ->numeric()
                            ->default(40)
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Siklus')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cycle_code')
                    ->label('Kode Siklus')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pond.name')
                    ->label('Kolam')
                    ->weight('medium')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('species')
                    ->label('Komoditas')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('current_doc')
                    ->label('DOC (Hari)')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => "DOC {$state}"),
                Tables\Columns\TextColumn::make('initial_population')
                    ->label('Populasi Awal')
                    ->numeric()
                    ->suffix(' ekor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tgl Tebar')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'primary',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Panen',
                        'cancelled' => 'Batal',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Panen',
                        'cancelled' => 'Batal',
                    ]),
                Tables\Filters\SelectFilter::make('pond_id')
                    ->label('Pilih Kolam')
                    ->relationship('pond', 'name'),
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
            'index' => Pages\ListCycles::route('/'),
            'create' => Pages\CreateCycle::route('/create'),
            'view' => Pages\ViewCycle::route('/{record}'),
            'edit' => Pages\EditCycle::route('/{record}/edit'),
        ];
    }
}
