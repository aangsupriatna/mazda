<?php

namespace App\Filament\Pages\Settings;

use App\Enums\Setting\Font;
use App\Enums\Setting\PrimaryColor;
use App\Enums\Setting\RecordsPerPage;
use App\Enums\Setting\TableSortDirection;
use App\Events\TampilanEvent;
use App\Models\Tampilan as TampilanModel;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

class Tampilan extends Page
{
    use InteractsWithFormActions;

    protected static ?string $model = TampilanModel::class;

    protected static string $view = 'filament.pages.settings.tampilan';

    // protected static ?string $navigationIcon = 'heroicon-o-photo';

    public ?array $data = [];

    #[Locked]
    public ?TampilanModel $record = null;

    public function getTitle(): string
    {
        return __('tampilan.tampilan');
    }

    public static function getNavigationLabel(): string
    {
        return __('tampilan.tampilan');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation.pengaturan');
    }

    public function mount(): void
    {
        $this->record = TampilanModel::firstOrNew([
            'perusahaan_id' => Filament::getTenant()->id,
            'user_id' => Auth::user()->id,
        ]);

        $this->fillForm();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getGeneralSection(),
                $this->getDataPresentationSection(),
            ])
            ->model($this->record)
            ->statePath('data')
            ->operation('edit');
    }

    protected function getGeneralSection(): Component
    {
        return Section::make(__('tampilan.general'))
            ->description(__('tampilan.deskripsi_general'))
            ->schema([
                Forms\Components\Select::make('primary_color')
                    ->label(__('tampilan.primary_color'))
                    ->allowHtml()
                    ->native(false)
                    ->options(
                        collect(PrimaryColor::cases())
                            ->sort(static fn ($a, $b) => $a->value <=> $b->value)
                            ->mapWithKeys(static fn ($case) => [
                                $case->value => "<span class='flex gap-x-4 items-center'>
                                <span class='w-4 h-4 rounded-full' style='background:rgb(" . $case->getColor()[600] . ")'></span>
                                <span>" . $case->getLabel() . '</span>
                                </span>',
                            ]),
                    )
                    ->default(PrimaryColor::DEFAULT)
                    ->required(),
                Forms\Components\Select::make('font')
                    ->label(__('tampilan.font'))
                    ->allowHtml()
                    ->native(false)
                    ->options(
                        collect(Font::cases())
                            ->mapWithKeys(static fn ($case) => [
                                $case->value => "<span style='font-family:{$case->getLabel()}'>{$case->getLabel()}</span>",
                            ]),
                    )
                    ->default(Font::DEFAULT)
                    ->required(),
                Forms\Components\Select::make('timezone')
                    ->label(__('tampilan.timezone'))
                    ->options(function () {
                        return collect(timezone_identifiers_list())
                            ->mapWithKeys(fn ($timezone) => [$timezone => $timezone]);
                    })
                    ->searchable()
                    ->default(config('app.timezone'))
                    ->required(),
            ])
            ->collapsible()
            ->columns(2);
    }

    protected function getDataPresentationSection(): Component
    {
        return Section::make(__('tampilan.data_presentation'))
            ->description(__('tampilan.deskripsi_data_presentation'))
            ->schema([
                Forms\Components\Select::make('table_sort_direction')
                    ->label(__('tampilan.table_sort_direction'))
                    ->native(false)
                    ->options(TableSortDirection::class)
                    ->default(TableSortDirection::DEFAULT)
                    ->required(),
                Forms\Components\Select::make('records_per_page')
                    ->label(__('tampilan.records_per_page'))
                    ->native(false)
                    ->options(RecordsPerPage::class)
                    ->default(RecordsPerPage::DEFAULT)
                    ->required(),
            ])
            ->collapsible()
            ->columns(2);
    }

    protected function handleRecordUpdate(TampilanModel $record, array $data): TampilanModel
    {
        $record->fill($data);

        $keysToWatch = [
            'primary_color',
            'font',
        ];

        if ($record->isDirty($keysToWatch)) {
            $this->dispatch('tampilanUpdated');
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
            ->label(__('tampilan.save'))
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

        $this->form->fill($data);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $data['perusahaan_id'] = Filament::getTenant()->id;
            $data['user_id'] = Auth::user()->id;

            $this->record = TampilanModel::updateOrCreate(
                ['perusahaan_id' => Filament::getTenant()->id, 'user_id' => Auth::user()->id],
                $data
            );

            // $this->handleRecordUpdate($this->record, $data);
            $this->dispatch('tampilanUpdated');
            event(new TampilanEvent($this->record));
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()->send();


    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('tampilan.saved'));
    }
}
