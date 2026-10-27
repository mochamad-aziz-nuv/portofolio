<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaterQualityLogResource\Pages;
use App\Models\WaterQualityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WaterQualityLogResource extends Resource
{
    protected static ?string $model = WaterQualityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Monitoring Operasional';
    protected static ?string $modelLabel = 'Kualitas Air';
    protected static ?string $pluralModelLabel = 'Catatan Kualitas Air';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengukuran')
                    ->schema([
                        Forms\Components\Select::make('pond_id')
                            ->label('Kolam')
                            ->relationship('pond', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('cycle_id')
                            ->label('Siklus Terkait (Opsional)')
                            ->relationship('cycle', 'cycle_code')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DateTimePicker::make('recorded_at')
                            ->label('Waktu Pengukuran')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('condition_status')
                            ->label('Status Kondisi Air')
                            ->options([
                                'optimal' => 'Optimal / Aman',
                                'warning' => 'Perhatian / Waspada',
                                'critical' => 'Kritis / Butuh Tindakan Segera',
                            ])
                            ->default('optimal')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Parameter Sensor Kualitas Air')
                    ->schema([
                        Forms\Components\TextInput::make('temperature')
                            ->label('Suhu Air (°C)')
                            ->numeric()
                            ->suffix('°C')
                            ->required(),
                        Forms\Components\TextInput::make('ph')
                            ->label('Tingkat Keasaman (pH)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('dissolved_oxygen')
                            ->label('Oksigen Terlarut (DO)')
                            ->numeric()
                            ->suffix('mg/L')
                            ->required(),
                        Forms\Components\TextInput::make('salinity')
                            ->label('Salinitas (Kadar Garam)')
                            ->numeric()
                            ->suffix('ppt')
                            ->required(),
                        Forms\Components\TextInput::make('turbidity')
                            ->label('Kecerahan Secchi Disc')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('ammonia')
                            ->label('Total Ammonia Nitrogen (TAN)')
                            ->numeric()
                            ->suffix('mg/L'),
                        Forms\Components\TextInput::make('alkalinity')
                            ->label('Alkalinitas')
                            ->numeric()
                            ->suffix('ppm'),
                        Forms\Components\Select::make('recorded_by')
                            ->label('Petugas Lapangan')
                            ->relationship('recorder', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Observasi Fisik Air (Warna, Bau, dsb)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('recorded_at')
                    ->label('Waktu Cek')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pond.name')
                    ->label('Kolam')
                    ->weight('medium')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dissolved_oxygen')
                    ->label('DO (mg/L)')
                    ->suffix(' mg/L')
                    ->numeric()
                    ->weight('bold')
                    ->color(fn ($state) => $state < 4.5 ? 'danger' : ($state < 5.0 ? 'warning' : 'success'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('ph')
                    ->label('pH')
                    ->numeric()
                    ->weight('medium')
                    ->color(fn ($state) => ($state < 7.5 || $state > 8.5) ? 'warning' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('temperature')
                    ->label('Suhu')
                    ->suffix('°C')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salinity')
                    ->label('Salinitas')
                    ->suffix(' ppt')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ammonia')
                    ->label('Amoniak')
                    ->suffix(' ppm')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('condition_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'optimal' => 'success',
                        'warning' => 'warning',
                        'critical' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'optimal' => 'Optimal',
                        'warning' => 'Perhatian',
                        'critical' => 'Kritis',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('recorder.name')
                    ->label('Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('recorded_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('pond_id')
                    ->label('Filter Kolam')
                    ->relationship('pond', 'name'),
                Tables\Filters\SelectFilter::make('condition_status')
                    ->label('Status Kondisi')
                    ->options([
                        'optimal' => 'Optimal',
                        'warning' => 'Perhatian',
                        'critical' => 'Kritis',
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
            'index' => Pages\ListWaterQualityLogs::route('/'),
            'create' => Pages\CreateWaterQualityLog::route('/create'),
            'view' => Pages\ViewWaterQualityLog::route('/{record}'),
            'edit' => Pages\EditWaterQualityLog::route('/{record}/edit'),
        ];
    }
}
