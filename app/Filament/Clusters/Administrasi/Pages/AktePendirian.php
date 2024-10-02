<?php

namespace App\Filament\Clusters\Administrasi\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Livewire\Attributes\Locked;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\Section;
use App\Filament\Clusters\Administrasi;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\AktePendirian as AktePendirianModel;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Pages\Concerns\InteractsWithFormActions;

class AktePendirian extends Page
{
    use InteractsWithFormActions;

    protected static ?string $model = AktePendirianModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static string $view = 'filament.clusters.administrasi.pages.akte-pendirian';

    protected static ?string $cluster = Administrasi::class;

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    #[Locked]
    public ?AktePendirianModel $record = null;

    public function getTitle(): string
    {
        return __('akte_pendirian.akte_pendirian');
    }

    public static function getNavigationLabel(): string
    {
        return __('akte_pendirian.akte_pendirian');
    }

    public function mount(): void
    {
        $perusahaanId = Filament::getTenant()->id;
        $this->record = AktePendirianModel::firstOrNew(['perusahaan_id' => $perusahaanId]);

        $this->fillForm();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getAktePendirianSection(),
                $this->getAktePengesahanSection(),
                $this->getLampiranAkteSection(),
            ])
            ->model($this->record)
            ->statePath('data')
            ->operation('edit');
    }

    protected function getAktePendirianSection(): Component
    {
        return Section::make(__('akte_pendirian.akte_pendirian'))
            ->description('Isi data Akte Pendirian Anda di sini.')
            ->schema([
                TextInput::make('nomor')
                    ->label(__('akte_pendirian.nomor_akte'))
                    ->required(),
                DatePicker::make('tanggal')
                    ->label(__('akte_pendirian.tanggal_akte'))
                    ->displayFormat('d mm Y')
                    ->native(false)
                    ->suffixIcon('heroicon-o-calendar')
                    ->required(),
                TextInput::make('nama_notaris')
                    ->label(__('akte_pendirian.nama_notaris'))
                    ->columnSpanFull()
                    ->required(),
            ])
            ->collapsible()
            ->columns(2);
    }

    protected function getAktePengesahanSection(): Component
    {
        return Section::make(__('akte_pendirian.akte_pengesahan'))
            ->description('Isi data Pengesahan Akte dari Kemenkumham di sini.')
            ->schema([
                TextInput::make('nomor_pengesahan')
                    ->label(__('akte_pendirian.nomor_pengesahan'))
                    ->required(),
                DatePicker::make('tanggal_pengesahan')
                    ->label(__('akte_pendirian.tanggal_pengesahan'))
                    ->suffixIcon('heroicon-o-calendar')
                    ->displayFormat('d mm Y')
                    ->native(false)
                    ->required(),
            ])
            ->collapsible()
            ->columns(2);
    }

    protected function getLampiranAkteSection(): Component
    {
        return Section::make(__('akte_pendirian.lampiran_akte'))
            ->description('Upload Lampiran Akte dan Pengesahan Akte di sini.')
            ->schema([
                CuratorPicker::make('file_akte_id')
                    ->label(__('akte_pendirian.file_akte'))
                    ->relationship('media', 'id')
                    ->orderColumn('order')
                    ->typeColumn('type')
                    ->constrained(true)
                    ->required(),
                CuratorPicker::make('file_pengesahan_id')
                    ->label(__('akte_pendirian.file_pengesahan'))
                    ->relationship('media', 'id')
                    ->orderColumn('order')
                    ->typeColumn('type')
                    ->constrained(true)
                    ->required(),
            ])
            ->collapsible()
            ->columns();
    }

    protected function handleRecordUpdate(AktePendirianModel $record, array $data): AktePendirianModel
    {
        $record->fill($data);

        $keysToWatch = [
            'nomor_akte',
            'tanggal_akte',
            'nama_notaris',
            'file_akte',
        ];

        if ($record->isDirty($keysToWatch)) {
            $this->dispatch('akteUpdated');
        }

        $record->save();

        return $record;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('akte_pendirian.save'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::ScreenTwoExtraLarge;
    }

    public function fillForm(): void
    {
        $data = $this->record->attributesToArray();
        // dd($data);
        $this->form->fill($data);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $data['perusahaan_id'] = Filament::getTenant()->id;

            $this->record->fill($data);
            $this->record->save();

            $this->getSavedNotification()->send();
        } catch (Halt $exception) {
            return;
        }
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('akte_pendirian.konfirmasi_akte'));
    }
}
