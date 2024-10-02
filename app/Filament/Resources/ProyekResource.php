<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Proyek;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use PhpOffice\PhpWord\TemplateProcessor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProyekResource\Pages;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Actions\Action as NotificationAction;
use App\Filament\Resources\ProyekResource\Widgets\ProyekStatsOverview;

class ProyekResource extends Resource
{
    protected static ?string $model = Proyek::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function getLabel(): string
    {
        return __('navigation.proyek');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.teknis');
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        $perusahaan = Filament::getTenant();

        if ($perusahaan) {
            return static::getModel()::where('created_at', '>=', now()->subDays(7))
                ->where('perusahaan_id', $perusahaan->id)
                ->count();
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getNavigationBadge() > 0 ? 'primary' : 'gray';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        // Detail Proyek
                        Forms\Components\Tabs\Tab::make(__('proyek.detail'))
                            ->icon('heroicon-o-magnifying-glass-circle')
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label(__('proyek.nama'))
                                    ->columnSpanFull()
                                    ->required(),
                                Forms\Components\TextInput::make('nomor_kontrak')
                                    ->label(__('proyek.nomor_kontrak'))
                                    ->required(),
                                Forms\Components\TextInput::make('nilai_kontrak')
                                    ->label(__('proyek.nilai_kontrak'))
                                    ->prefix('Rp. ')
                                    ->mask(RawJs::make('$money($input)', ['money' => 'formatMoney']))
                                    ->stripCharacters(',')
                                    ->required(),
                                Forms\Components\Select::make('kategori_proyek')
                                    ->label(__('proyek.kategori_proyek'))
                                    ->native(false)
                                    ->options([
                                        'Barang' => 'Barang',
                                        'Konstruksi' => 'Konstruksi',
                                        'Jasa Lainnya' => 'Jasa Lainnya',
                                        'Jasa Konsultansi (Perorangan)' => 'Jasa Konsultansi (Perorangan)',
                                        'Jasa Konsultansi Badan Usaha' => 'Jasa Konsultansi Badan Usaha',
                                        'Jasa Konsultansi Badan Usaha Non Konstruksi' => 'Jasa Konsultansi Badan Usaha Non Konstruksi',
                                        'Jasa Konsultansi Perorangan Konstruksi' => 'Jasa Konsultansi Perorangan Konstruksi',
                                        'Pekerjaan Konstruksi Terintegrasi' => 'Pekerjaan Konstruksi Terintegrasi',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('lokasi')
                                    ->label(__('proyek.lokasi'))
                                    ->required(),
                                Forms\Components\Select::make('klien_id')
                                    ->label(__('proyek.klien'))
                                    ->relationship('klien', 'nama')
                                    ->searchable('klien', 'nama')
                                    ->preload()
                                    ->native(false)
                                    ->columnSpanFull()
                                    ->required()
                                    ->optionsLimit(50)
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        return view('filament.components.select-klien-option', [
                                            'klien' => $record,
                                        ])->render();
                                    })
                                    ->allowHtml()
                                    ->createOptionForm([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('nama')
                                                    ->label(__('klien.nama'))
                                                    ->required(),
                                                Forms\Components\TextInput::make('alias')
                                                    ->label(__('klien.alias'))
                                                    ->required(),
                                            ])->columns(2),
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('nomor_telepon')
                                                    ->label(__('klien.nomor_telepon'))
                                                    ->tel()
                                                    ->required(),
                                                Forms\Components\TextInput::make('website')
                                                    ->label(__('klien.website'))
                                                    ->required(),
                                                Forms\Components\Textarea::make('alamat')
                                                    ->label(__('klien.alamat'))
                                                    ->columnSpanFull()
                                                    ->rows(3)
                                                    ->required(),
                                            ])->columns(2),
                                        CuratorPicker::make('logo_id')
                                            ->label(__('klien.logo'))
                                            ->constrained(true)
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make(__('proyek.durasi_proyek'))
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_mulai')
                                    ->label(__('proyek.tanggal_mulai'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_selesai')
                                    ->label(__('proyek.tanggal_selesai'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_serah_terima')
                                    ->label(__('proyek.tanggal_serah_terima'))
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false)
                                    ->required(),
                            ])
                            ->columnSpan(['lg' => 1]),
                        // Kualifikasi Proyek
                        Forms\Components\Tabs\Tab::make(__('proyek.kualifikasi_proyek'))
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Forms\Components\TagsInput::make('klasifikasi')
                                    ->label(__('proyek.klasifikasi'))
                                    ->suggestions(function () {
                                        $cachedKlasifikasi = Cache::get('proyek_klasifikasi', '');
                                        return array_map('trim', explode(',', $cachedKlasifikasi));
                                    })
                                    ->placeholder(__('proyek.bidang_klasifikasi'))
                                    ->separator(','),
                                Forms\Components\Textarea::make('ruang_lingkup_pekerjaan')
                                    ->label(__('proyek.ruang_lingkup_pekerjaan'))
                                    ->rows(5)
                                    ->nullable(),
                                Forms\Components\Textarea::make('deskripsi')
                                    ->label(__('proyek.deskripsi'))
                                    ->rows(5)
                                    ->nullable(),
                            ]),

                        // Lampiran Proyek
                        Forms\Components\Tabs\Tab::make(__('proyek.lampiran_proyek'))
                            ->icon('heroicon-o-paper-clip')
                            ->schema([
                                Forms\Components\Repeater::make('lampiran')
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->label('')
                                    ->collapsed(fn($state): bool => !empty($state))
                                    ->itemLabel(
                                        fn(array $state): ?string => ($state['nama'] ?? '')
                                    )
                                    ->schema(
                                        static::getLampiranProyekFormSchema()
                                    )
                                    ->deleteAction(
                                        fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                    ),
                            ]),
                    ])->columnSpan(['lg' => 2]),
                // Kontrak Proyek
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('proyek.durasi_proyek'))
                            ->description(__('proyek.deskripsi_durasi'))
                            ->collapsible()
                            ->schema([
                                Forms\Components\TextInput::make('persentase_pekerjaan')
                                    ->label(__('proyek.persentase_pekerjaan'))
                                    ->integer()
                                    ->required(),
                                Forms\Components\Toggle::make('konsorsium')
                                    ->label(__('proyek.konsorsium'))
                                    ->default(false)
                                    ->columnSpanFull()
                                    ->inlineLabel(false),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->deferLoading()
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label(__('klien.logo'))
                    ->toggleable()
                    ->size(24)
                    ->defaultImageUrl(function ($record) {
                        return Storage::url($record->klien->logo?->path);
                    })
                    ->square(),
                Tables\Columns\TextColumn::make('nama')
                    ->label(__('proyek.nama'))
                    ->wrap()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lokasi')
                    ->label(__('proyek.lokasi'))
                    ->wrap()
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('klien.nama')
                    ->label(__('proyek.klien'))
                    ->wrap()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('klasifikasi')
                //     ->label(__('proyek.klasifikasi'))
                //     ->formatStateUsing(function ($state) {
                //         if (is_string($state)) {
                //             $items = array_map('trim', explode(',', $state));
                //         } else {
                //             $items = is_array($state) ? $state : [];
                //         }
                //         return collect($items)
                //             ->filter()
                //             ->map(function ($item) {
                //                 return "<span class='inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full border border-gray-200 dark:bg-gray-700 dark:text-white'>{$item}</span>";
                //             })
                //             ->implode(' ');
                //     })
                //     ->html()
                //     ->wrap()
                //     ->toggleable()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('kategori_proyek')
                    ->label(__('proyek.kategori_proyek'))
                    ->wrap()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai_kontrak')
                    ->label(__('proyek.nilai_kontrak'))
                    ->wrapHeader()
                    ->money('IDR', locale: 'id')
                    ->alignEnd()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_serah_terima')
                    ->label(__('proyek.tanggal_serah_terima'))
                    ->date('d M Y')
                    ->alignCenter()
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->defaultSort('tanggal_serah_terima', 'desc')
            ->filters([
                SelectFilter::make('tanggal_mulai_range')
                    ->label(__('proyek.tanggal_mulai_range'))
                    ->options([
                        '3_years' => __('proyek.3_tahun_terakhir'),
                        '10_years' => __('proyek.10_tahun_terakhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function (Builder $query, string $value) {
                            $years = $value === '3_years' ? 3 : 10;
                            return $query->where('tanggal_mulai', '>=', Carbon::now()->subYears($years));
                        });
                    }),
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('klasifikasi')
                    ->multiple()
                    ->options(function () {
                        $cachedKlasifikasi = Cache::get('proyek_klasifikasi', '');
                        $klasifikasi = array_map('trim', explode(',', $cachedKlasifikasi));
                        return array_combine($klasifikasi, $klasifikasi);
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['values'],
                            fn(Builder $query, $values): Builder => $query->where(function ($query) use ($values) {
                                foreach ($values as $value) {
                                    $query->orWhere('klasifikasi', 'like', "%$value%");
                                }
                            })
                        );
                    })
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
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('proyek.ekspor_proyek'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $proyekIds = $records->pluck('id')->toArray();
                            static::exportMultipleProyek($proyekIds);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('kategori_proyek')
                    ->label(__('proyek.kategori_proyek'))
                    ->collapsible(),
                Tables\Grouping\Group::make('klien.nama')
                    ->label(__('proyek.klien'))
                    ->collapsible(),
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
            'index' => Pages\ListProyeks::route('/'),
            'create' => Pages\CreateProyek::route('/create'),
            // 'view' => Pages\ViewProyek::route('/{record}'),
            'edit' => Pages\EditProyek::route('/{record}/edit'),
        ];
    }

    public static function getLampiranProyekFormSchema(): array
    {
        return [
            Forms\Components\Select::make('nama')
                ->label(__('proyek.nama_lampiran'))
                ->options([
                    'Dokumen Prakualifikasi' => 'Dokumen Prakualifikasi',
                    'Dokumen Tender' => 'Dokumen Tender',
                    'Dokumen Kontrak' => 'Dokumen Kontrak',
                    'BAST' => 'BAST',
                    'Dokumen Lainnya' => 'Dokumen Lainnya',
                ])
                ->native(false),
            CuratorPicker::make('lampiran')
                ->label(__('proyek.lampiran'))
                ->constrained(true),
        ];
    }

    public function exportSelected()
    {
        $selectedRecords = $this->getSelectedTableRecords();
        $proyekIds = $selectedRecords->pluck('id')->toArray();

        ProyekResource::exportMultipleProyek($proyekIds);
    }

    public static function exportMultipleProyek(array $proyekIds): void
    {
        $templateProcessor = new TemplateProcessor(resource_path('templates/proyek_template.docx'));

        $proyekData = [];
        foreach ($proyekIds as $index => $proyekId) {
            $proyek = Proyek::find($proyekId);
            $proyekData[] = [
                'nomor' => $index + 1,
                'nama' => $proyek->nama,
                'kategori_proyek' => $proyek->kategori_proyek,
                'lokasi' => $proyek->lokasi,
                'klien' => $proyek->klien->nama,
                'nomor_kontrak' => $proyek->nomor_kontrak,
                'nilai_kontrak' => 'Rp. ' . number_format($proyek->nilai_kontrak, 0, ',', '.'),
                'tanggal_mulai' => static::formatTanggal($proyek->tanggal_mulai),
                'tanggal_selesai' => static::formatTanggal($proyek->tanggal_selesai),
                'tanggal_serah_terima' => static::formatTanggal($proyek->tanggal_serah_terima),
            ];
        }

        $templateProcessor->cloneRow('nomor', count($proyekData));

        foreach ($proyekData as $index => $data) {
            $rowIndex = $index + 1;
            foreach ($data as $key => $value) {
                $templateProcessor->setValue($key . '#' . $rowIndex, $value);
            }
        }

        $exportDirectory = 'public/exports';
        $fileName = 'Proyek_' . date('YmdHis') . '.docx';
        $filePath = Storage::path($exportDirectory . '/' . $fileName);

        // Pastikan direktori ada
        if (!Storage::exists($exportDirectory)) {
            Storage::makeDirectory($exportDirectory);
        }

        $templateProcessor->saveAs($filePath);

        $url = Storage::url($exportDirectory . '/' . $fileName);

        Notification::make()
            ->title(__('proyek.ekspor_proyek_berhasil'))
            ->success()
            ->actions([
                NotificationAction::make('download')
                    ->label(__('proyek.unduh'))
                    ->url($url)
                    ->openUrlInNewTab(),
            ])
            ->send();
    }

    private static function formatTanggal($tanggal)
    {
        Carbon::setLocale(config('app.locale'));
        if (is_string($tanggal)) {
            return Carbon::parse($tanggal)->format('d F Y');
        }
        return $tanggal->format('d F Y');
    }

    public static function getWidgets(): array
    {
        return [
            ProyekStatsOverview::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('proyek.informasi_proyek'))
                    ->schema([
                        Infolists\Components\ViewEntry::make('klien')
                            ->view('filament.components.klien-info')
                            ->label(__('proyek.klien')),
                        Infolists\Components\TextEntry::make('nama')
                            ->label(__('proyek.nama')),
                        Infolists\Components\TextEntry::make('kategori_proyek')
                            ->label(__('proyek.kategori_proyek')),
                        Infolists\Components\TextEntry::make('lokasi'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('proyek.detail_kontrak'))
                    ->schema([
                        Infolists\Components\TextEntry::make('nomor_kontrak')
                            ->label(__('proyek.nomor_kontrak')),
                        Infolists\Components\TextEntry::make('nilai_kontrak')
                            ->label(__('proyek.nilai_kontrak'))
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('tanggal_mulai')
                            ->label(__('proyek.tanggal_mulai'))
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('tanggal_selesai')
                            ->label(__('proyek.tanggal_selesai'))
                            ->date('d F Y'),
                        Infolists\Components\TextEntry::make('tanggal_serah_terima')
                            ->label(__('proyek.tanggal_serah_terima'))
                            ->date('d F Y'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('proyek.kualifikasi_proyek'))
                    ->schema([
                        Infolists\Components\TextEntry::make('klasifikasi.kode')
                            ->label(__('proyek.kode_klasifikasi')),
                        Infolists\Components\TextEntry::make('klasifikasi.judul')
                            ->label(__('proyek.judul_klasifikasi')),
                        Infolists\Components\TextEntry::make('ruang_lingkup_pekerjaan')
                            ->label(__('proyek.ruang_lingkup_pekerjaan'))
                            ->markdown(),
                        Infolists\Components\TextEntry::make('deskripsi')
                            ->markdown(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('proyek.informasi_tambahan'))
                    ->schema([
                        Infolists\Components\IconEntry::make('konsorsium')
                            ->label(__('proyek.konsorsium'))
                            ->boolean(),
                        Infolists\Components\TextEntry::make('persentase_pekerjaan')
                            ->label(__('proyek.persentase_pekerjaan'))
                            ->suffix('%'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('proyek.lampiran'))
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('lampiran')
                            ->label(__('proyek.lampiran'))
                            ->schema([
                                Infolists\Components\TextEntry::make('nama')
                                    ->label(__('proyek.nama_lampiran')),
                                Infolists\Components\TextEntry::make('lampiran')
                                    ->label(__('proyek.file_lampiran'))
                                    ->icon('heroicon-o-paper-clip')
                                    ->url(fn($state) => is_array($state) ? Storage::url($state['lampiran'] ?? '') : Storage::url($state))
                                    ->formatStateUsing(fn($state) => is_array($state) ? basename($state['lampiran'] ?? '') : basename($state))
                                    ->openUrlInNewTab(),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Klien' => $record->klien->nama,
            'Lokasi' => $record->lokasi,
            'Kategori Proyek' => $record->kategori_proyek,
            'Nilai Kontrak' => 'Rp. ' . number_format($record->nilai_kontrak, 0, ',', '.'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'lokasi', 'klien.nama', 'nomor_kontrak', 'kategori_proyek'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }
}
