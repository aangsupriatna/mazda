<?php

namespace App\Filament\Clusters\Perpajakan\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Livewire\Attributes\Locked;
use App\Models\Npwp as NpwpModel;
use App\Filament\Clusters\Perpajakan;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Pages\Concerns\InteractsWithFormActions;

class Npwp extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.clusters.perpajakan.pages.npwp';

    protected static ?string $cluster = Perpajakan::class;

    use InteractsWithFormActions;

    public ?array $data = [];

    public $lampiran;

    protected static ?string $model = NpwpModel::class;

    #[Locked]
    public ?NpwpModel $record = null;

    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return __('npwp.npwp');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.npwp');
    }

    public function mount(): void
    {
        $perusahaanId = Filament::getTenant()->id;
        $this->record = NpwpModel::firstOrNew(['perusahaan_id' => $perusahaanId]);
        $this->fillForm();
    }

    public function fillForm(): void
    {
        $data = $this->record->attributesToArray();
        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make([
                Forms\Components\Section::make(__('npwp.npwp'))
                    ->description(__('npwp.deskripsi_npwp'))
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label(__('npwp.nama'))
                            ->required(),
                        Forms\Components\TextInput::make('nomor')
                            ->label(__('npwp.nomor'))
                            ->mask('99.999.999.9-999.999')
                            ->placeholder('XX.XXX.XXX.X-XXX.XXX')
                            ->maxLength(20)
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->date('d F Y')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->required(),
                    ]),
            ])->columnSpan(3),
            Forms\Components\Group::make([
                Forms\Components\Section::make(__('npwp.lampiran'))
                    ->description(__('npwp.deskripsi_lampiran'))
                    ->collapsible()
                    ->schema([
                        CuratorPicker::make('file_lampiran_id')
                            ->label(__('npwp.lampiran_npwp'))
                            ->constrained(true)
                            ->required(),
                    ]),
            ]),
        ])
            ->model($this->record)
            ->statePath('data')
            ->operation('edit')
            ->columns(4);
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
            ->label(__('npwp.simpan'))
            ->submit('save')
            ->keyBindings(['mod+s']);
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

    protected function handleRecordUpdate(NpwpModel $record, array $data): NpwpModel
    {
        $record->fill($data);

        $keysToWatch = [
            'nama',
            'nomor',
            'tanggal',
            'lampiran',
        ];

        if ($record->isDirty($keysToWatch)) {
            $this->dispatch('npwpUpdated');
        }

        $record->save();

        return $record;
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('npwp.konfirmasi_npwp'));
    }
}
