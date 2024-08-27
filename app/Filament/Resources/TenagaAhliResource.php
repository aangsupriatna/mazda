<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenagaAhliResource\Pages;
use App\Models\TenagaAhli;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenagaAhliResource extends Resource
{
    protected static ?string $model = TenagaAhli::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return __('navigation.tenaga_ahli');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.teknis');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Section::make(__('tenaga_ahli.identitas'))
                                ->description(__('tenaga_ahli.deskripsi_tenaga_ahli'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('nama')
                                        ->label(__('tenaga_ahli.nama'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Select::make('jenis_kelamin')
                                        ->label(__('tenaga_ahli.jenis_kelamin'))
                                        ->options([
                                            'Laki-laki' => 'Laki-laki',
                                            'Perempuan' => 'Perempuan',
                                        ])
                                        ->native(false),
                                    Forms\Components\DatePicker::make('tanggal_lahir')
                                        ->label(__('tenaga_ahli.tanggal_lahir'))
                                        ->date('d M Y')
                                        ->required()
                                        ->native(false)
                                        ->suffixIcon('heroicon-o-calendar'),
                                    Forms\Components\TextInput::make('kewarganegaraan')
                                        ->label(__('tenaga_ahli.kewarganegaraan'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('nik_paspor')
                                        ->label(__('tenaga_ahli.nik_paspor'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('npwp')
                                        ->label(__('tenaga_ahli.npwp'))
                                        ->mask('99.999.999.9-999.999')
                                        ->placeholder('XX.XXX.XXX.X-XXX.XXX')
                                        ->required()
                                        ->maxLength(20),
                                    Forms\Components\TextInput::make('negara_tempat_lahir')
                                        ->label(__('tenaga_ahli.negara_tempat_lahir'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('kabupaten_kota_tempat_lahir')
                                        ->label(__('tenaga_ahli.kabupaten_kota_tempat_lahir'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('no_bpjs_kesehatan')
                                        ->label(__('tenaga_ahli.no_bpjs_kesehatan'))
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('no_bpjs_ketenagakerjaan')
                                        ->label(__('tenaga_ahli.no_bpjs_ketenagakerjaan'))
                                        ->maxLength(255),
                                ])
                                ->columns(),
                            Forms\Components\Section::make(__('tenaga_ahli.alamat'))
                                ->description(__('tenaga_ahli.deskripsi_alamat'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Textarea::make('alamat')
                                        ->label(__('tenaga_ahli.alamat'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Group::make([
                                        Forms\Components\TextInput::make('kabupaten_kota')
                                            ->label(__('tenaga_ahli.kabupaten_kota'))
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('provinsi')
                                            ->label(__('tenaga_ahli.provinsi'))
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                        ->columns(),
                                ]),
                            Forms\Components\Section::make(__('tenaga_ahli.detail_tenaga_ahli'))
                                ->description(__('tenaga_ahli.deskripsi_detail_tenaga_ahli'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Select::make('jenis_tenaga_ahli')
                                        ->label(__('tenaga_ahli.jenis_tenaga_ahli'))
                                        ->options([
                                            'Individu WNI' => 'Individu WNI',
                                            'Individu WNA' => 'Individu WNA',
                                        ]),
                                    Forms\Components\Select::make('status_kepegawaian')
                                        ->label(__('tenaga_ahli.status_kepegawaian'))
                                        ->options([
                                            'Tetap' => 'Tetap',
                                            'Tidak Tetap' => 'Tidak Tetap',
                                        ]),
                                    Forms\Components\TextInput::make('lama_pengalaman_kerja')
                                        ->label(__('tenaga_ahli.lama_pengalaman_kerja'))
                                        ->integer()
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('profesi_keahlian')
                                        ->label(__('tenaga_ahli.profesi_keahlian'))
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->columns(),
                            Forms\Components\Section::make(__('tenaga_ahli.pendidikan'))
                                ->description(__('tenaga_ahli.deskripsi_pendidikan'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('pendidikan')
                                        ->label('')
                                        ->defaultItems(0)
                                        ->collapsible()
                                        ->collapsed(fn($state): bool => !empty($state))
                                        ->itemLabel(
                                            fn(array $state): ?string => ($state['jenjang'] . ' ' . $state['jurusan'] ?? '')
                                        )
                                        ->schema([
                                            Forms\Components\TextInput::make('jenjang')
                                                ->label(__('tenaga_ahli.jenjang'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('jurusan')
                                                ->label(__('tenaga_ahli.jurusan'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('universitas')
                                                ->label(__('tenaga_ahli.universitas'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('tahun_lulus')
                                                ->label(__('tenaga_ahli.tahun_lulus'))
                                                ->required()
                                                ->maxLength(255),
                                        ])
                                        ->deleteAction(
                                            fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        )
                                        ->columns(),
                                ]),
                            Forms\Components\Section::make(__('tenaga_ahli.sertifikasi_keahlian'))
                                ->description(__('tenaga_ahli.deskripsi_sertifikasi_keahlian'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('sertifikasi_keahlian')
                                        ->label('')
                                        ->defaultItems(0)
                                        ->collapsible()
                                        ->collapsed(fn($state): bool => !empty($state))
                                        ->itemLabel(
                                            fn(array $state): ?string => ($state['keahlian'] ?? '')
                                        )
                                        ->schema([
                                            Forms\Components\TextInput::make('keahlian')
                                                ->label(__('tenaga_ahli.keahlian'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('no_sertifikat')
                                                ->label(__('tenaga_ahli.no_sertifikat'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\FileUpload::make('file_sertifikat')
                                                ->label(__('tenaga_ahli.file_sertifikat'))
                                                ->columnSpanFull(),
                                        ])
                                        ->deleteAction(
                                            fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        )
                                        ->columns(),
                                ]),
                            Forms\Components\Section::make(__('riwayat_pengalaman_kerja.riwayat_pengalaman_kerja'))
                                ->description(__('riwayat_pengalaman_kerja.deskripsi_riwayat_pengalaman_kerja'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('riwayat_pengalaman_kerja')
                                        ->relationship()
                                        ->label('')
                                        ->defaultItems(0)
                                        ->collapsible()
                                        ->collapsed(fn($state): bool => !empty($state))
                                        ->itemLabel(
                                            fn(array $state): ?string => ($state['nama_proyek'] . ' - ' . $state['tahun'] ?? '')
                                        )
                                        ->schema([
                                            Forms\Components\Textarea::make('nama_proyek')
                                                ->label(__('riwayat_pengalaman_kerja.nama_proyek'))
                                                ->required()
                                                ->columnSpanFull(),
                                            Forms\Components\TextInput::make('tahun')
                                                ->label(__('riwayat_pengalaman_kerja.tahun'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('lokasi_proyek')
                                                ->label(__('riwayat_pengalaman_kerja.lokasi_proyek'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('pengguna_jasa')
                                                ->label(__('riwayat_pengalaman_kerja.pengguna_jasa'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\TextInput::make('nama_perusahaan')
                                                ->label(__('riwayat_pengalaman_kerja.nama_perusahaan'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\DatePicker::make('tanggal_mulai')
                                                ->label(__('riwayat_pengalaman_kerja.tanggal_mulai'))
                                                ->native(false)
                                                ->required(),
                                            Forms\Components\DatePicker::make('tanggal_selesai')
                                                ->label(__('riwayat_pengalaman_kerja.tanggal_selesai'))
                                                ->native(false)
                                                ->required(),
                                            Forms\Components\TextInput::make('posisi_penugasan')
                                                ->label(__('riwayat_pengalaman_kerja.posisi_penugasan'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\Select::make('status_kepegawaian')
                                                ->label(__('riwayat_pengalaman_kerja.status_kepegawaian'))
                                                ->options([
                                                    'Tetap' => 'Tetap',
                                                    'Tidak Tetap' => 'Tidak Tetap',
                                                ])
                                                ->required(),
                                            Forms\Components\Textarea::make('uraian_tugas')
                                                ->label(__('riwayat_pengalaman_kerja.uraian_tugas'))
                                                ->required()
                                                ->rows(5)
                                                ->columnSpanFull(),
                                            Forms\Components\FileUpload::make('surat_referensi')
                                                ->label(__('riwayat_pengalaman_kerja.surat_referensi'))
                                                ->disk('public')
                                                ->directory('surat_referensi')
                                                ->downloadable()
                                                ->columnSpanFull(),
                                        ])
                                        ->addActionLabel(__('riwayat_pengalaman_kerja.tambahkan'))
                                        ->deleteAction(
                                            fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        )
                                        ->columns(2),
                                ]),
                        ])->columnSpan(2),
                        Forms\Components\Group::make([
                            Forms\Components\Section::make(__('tenaga_ahli.kontak'))
                                ->description(__('tenaga_ahli.deskripsi_kontak'))
                                ->collapsible()
                                ->schema([
                                    Forms\Components\TextInput::make('nomor_telepon_hp')
                                        ->label(__('tenaga_ahli.nomor_telepon_hp'))
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('email')
                                        ->label(__('tenaga_ahli.email'))
                                        ->email()
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('website')
                                        ->label(__('tenaga_ahli.website'))
                                        ->url()
                                        ->maxLength(255),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label(__('tenaga_ahli.nama'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label(__('tenaga_ahli.jenis_kelamin'))
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label(__('tenaga_ahli.tanggal_lahir'))
                    ->wrapHeader()
                    ->date('d M Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('profesi_keahlian')
                    ->label(__('tenaga_ahli.profesi_keahlian'))
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('kewarganegaraan')
                    ->label(__('tenaga_ahli.kewarganegaraan'))
                    ->wrapHeader()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('lama_pengalaman_kerja')
                    ->label(__('tenaga_ahli.lama_pengalaman_kerja'))
                    ->wrapHeader()
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->weight('bold')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenagaAhlis::route('/'),
            'create' => Pages\CreateTenagaAhli::route('/create'),
            'view' => Pages\ViewTenagaAhli::route('/{record}'),
            'edit' => Pages\EditTenagaAhli::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
