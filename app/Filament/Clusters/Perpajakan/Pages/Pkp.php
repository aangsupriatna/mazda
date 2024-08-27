<?php

namespace App\Filament\Clusters\Perpajakan\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use App\Models\Pkp as PkpModel;
use Livewire\Attributes\Locked;
use App\Filament\Clusters\Perpajakan;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Pages\Concerns\InteractsWithFormActions;

class Pkp extends Page
{
    use InteractsWithFormActions;

    public ?array $data = [];

    public $lampiran;

    protected static ?string $model = PkpModel::class;

    #[Locked]
    public ?PkpModel $record = null;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'filament.clusters.perpajakan.pages.pkp';

    protected static ?string $cluster = Perpajakan::class;

    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return __('pkp.pkp');
    }

    public static function getNavigationLabel(): string
    {
        return __('navigation.pkp');
    }

    public function mount(): void
    {
        $perusahaanId = Filament::getTenant()->id;
        $this->record = PkpModel::firstOrNew(['perusahaan_id' => $perusahaanId]);
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
                Forms\Components\Section::make(__('pkp.pkp'))
                    ->collapsible()
                    ->description(__('pkp.deskripsi_pkp'))
                    ->schema([
                        Forms\Components\TextInput::make('nomor')
                            ->label(__('pkp.nomor'))
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal')
                            ->date('d F Y')
                            ->native(false)
                            ->suffixIcon('heroicon-o-calendar')
                            ->required(),
                    ]),
            ])->columnSpan(3),
            Forms\Components\Group::make([
                Forms\Components\Section::make(__('pkp.lampiran'))
                    ->description(__('pkp.deskripsi_lampiran'))
                    ->collapsible()
                    ->schema([
                        CuratorPicker::make('file_lampiran_id')
                            ->label(__('pkp.lampiran_pkp'))
                            ->constrained(true)
                            ->required(),
                    ]),
            ]),
        ])
            ->columns(4)
            ->model($this->record)
            ->statePath('data')
            ->operation('edit');
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
            ->label(__('pkp.simpan'))
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

    protected function handleRecordUpdate(PkpModel $record, array $data): PkpModel
    {
        $record->fill($data);

        $keysToWatch = [
            'nomor',
            'tanggal',
            'lampiran',
        ];

        if ($record->isDirty($keysToWatch)) {
            $this->dispatch('pkpUpdated');
        }

        $record->save();

        return $record;
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('pkp.konfirmasi_pkp'));
    }
}
