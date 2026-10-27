<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $modelLabel = 'Pengguna & Role';
    protected static ?string $pluralModelLabel = 'Daftar Pengguna';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun & Peran')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone')
                            ->label('No. WhatsApp / HP')
                            ->tel()
                            ->placeholder('08123456789'),
                        Forms\Components\Select::make('role')
                            ->label('Peran Tambak (Role)')
                            ->options([
                                'admin' => 'Administrator Pusat',
                                'supervisor' => 'Supervisor Kolam',
                                'teknisi_utama' => 'Teknisi Utama Tambak',
                                'asisten_teknisi' => 'Asisten Teknisi Lapangan',
                                'operator_pakan' => 'Operator Pakan Harian',
                            ])
                            ->default('teknisi_utama')
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi (Password)')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. Kontak')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Peran / Jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'supervisor' => 'primary',
                        'teknisi_utama' => 'success',
                        'asisten_teknisi' => 'info',
                        'operator_pakan' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'admin' => 'Admin Pusat',
                        'supervisor' => 'Supervisor',
                        'teknisi_utama' => 'Teknisi Utama',
                        'asisten_teknisi' => 'Asisten Teknisi',
                        'operator_pakan' => 'Operator Pakan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filter Peran')
                    ->options([
                        'admin' => 'Administrator Pusat',
                        'supervisor' => 'Supervisor Kolam',
                        'teknisi_utama' => 'Teknisi Utama Tambak',
                        'asisten_teknisi' => 'Asisten Teknisi Lapangan',
                        'operator_pakan' => 'Operator Pakan Harian',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
