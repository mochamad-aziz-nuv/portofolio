<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingLogResource\Pages;
use App\Models\FeedingLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeedingLogResource extends Resource
{
    protected static ?string $model = FeedingLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?string $navigationGroup = 'Monitoring Operasional';
    protected static ?string $modelLabel = 'Log Pakan';
    protected static ?string $pluralModelLabel = 'Catatan Pakan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pemberian Pakan')
                    ->schema([
                        Forms\Components\Select::make('cycle_id')
                            ->label('Siklus Budidaya')
                            ->relationship('cycle', 'cycle_code')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('feed_brand')
                            ->label('Merek / Tipe Pakan')
                            ->default('Megami #2')
                            ->required(),
                        Forms\Components\DatePicker::make('feeding_date')
                            ->label('Tanggal Pakan')
                            ->default(now())
                            ->required(),
                        Forms\Components\TimePicker::make('feeding_time')
                            ->label('Jam Pemberian')
                            ->default('07:00')
                            ->required(),
                        Forms\Components\TextInput::make('doc')
                            ->label('DOC (Hari Budidaya)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('amount_kg')
                            ->label('Jumlah Pakan (kg)')
                            ->numeric()
                            ->suffix('kg')
                            ->required(),
                        Forms\Components\Select::make('appetite_rate')
                            ->label('Cek Anco / Nafsu Makan')
                            ->options([
                                'optimal' => 'Habis Total (Optimal)',
                                'normal' => 'Normal (Sisa Sedikit)',
                                'low' => 'Kurang Nafsu Makan',
                                'remaining_feed' => 'Sisa Banyak (Evaluasi)',
                            ])
                            ->default('optimal')
                            ->required(),
                        Forms\Components\Select::make('recorded_by')
                            ->label('Teknisi Pencatat')
                            ->relationship('recorder', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Lapangan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('feeding_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feeding_time')
                    ->label('Waktu')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('cycle.cycle_code')
                    ->label('Siklus')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cycle.pond.name')
                    ->label('Kolam')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc')
                    ->label('DOC')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => "DOC {$state}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_kg')
                    ->label('Pakan')
                    ->suffix(' kg')
                    ->numeric()
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed_brand')
                    ->label('Merek')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('appetite_rate')
                    ->label('Respon Anco')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'optimal' => 'success',
                        'normal' => 'info',
                        'low' => 'warning',
                        'remaining_feed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'optimal' => 'Optimal (Habis)',
                        'normal' => 'Normal',
                        'low' => 'Rendah',
                        'remaining_feed' => 'Sisa Banyak',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('recorder.name')
                    ->label('Teknisi')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('feeding_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('cycle_id')
                    ->label('Filter Siklus')
                    ->relationship('cycle', 'cycle_code'),
                Tables\Filters\SelectFilter::make('appetite_rate')
                    ->label('Kondisi Nafsu Makan')
                    ->options([
                        'optimal' => 'Optimal (Habis)',
                        'normal' => 'Normal',
                        'low' => 'Rendah',
                        'remaining_feed' => 'Sisa Banyak',
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
            'index' => Pages\ListFeedingLogs::route('/'),
            'create' => Pages\CreateFeedingLog::route('/create'),
            'view' => Pages\ViewFeedingLog::route('/{record}'),
            'edit' => Pages\EditFeedingLog::route('/{record}/edit'),
        ];
    }
}
