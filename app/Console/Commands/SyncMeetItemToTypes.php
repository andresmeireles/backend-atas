<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MeetItem;
use App\Models\MeetType;
use App\Service\Meet\AttachItemToType;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SyncMeetItemToTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:sync-meet-item-to-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bind meet item to types';

    public function __construct(
        private readonly MeetType $meetType,
        private readonly MeetItem $meetItem,
        private readonly AttachItemToType $attachItemToType
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $types = $this->meetType->all();
        $items = $this->meetItem->all();
        if ($types->isEmpty() || $items->isEmpty()) {
            $this->error('No data to be attached');
            return;
        }

        $getName = fn (MeetType|MeetItem $t): string => $t->name;
        /** @var string */
        $selectType =  $this->choice('Select type to bind', $types->map($getName(...))->toArray(), 0);
        $selectedType = $types->firstWhere(fn (MeetType $t) => $t->name === $selectType);
        if ($selectedType === null) {
            $this->error('Type not found');
            return;
        }
        $meetItems = $selectedType->items->map($getName(...));

        /** Choose options for meet type */
        $selectItem = $this->choice(
            question: 'Select items',
            choices: $items->map(fn (MeetItem $item) => $this->showItems($item, $meetItems))->toArray(),
            default: null,
            attempts: null,
            multiple: false,
        );

        if (is_array($selectItem)) {
            $selectItem = $selectItem[0];
        }

        $selectedItem = $items->firstWhere(fn (MeetItem $i) => $i->name === $selectItem);
        if ($selectedItem === null) {
            $this->error('Item not found');
            return;
        }

        $isRepeatable = $this->confirm('Is repeatable?', false);
        $isObligatory = $this->confirm('Is obligatory?', false);
        $statement = sprintf(
            'Bind %s to [%s] - repeatable: %s, obligatory: %s',
            $selectedType->name,
            $selectItem,
            $isRepeatable ? 'yes' : 'no',
            $isObligatory ? 'yes' : 'no'
        );
        $confirm = $this->confirm($statement, true);
        if ($confirm) {
            try {
                $this->attachItemToType->attachItem($selectedType->id, $selectedItem, $isRepeatable, $isObligatory);
                $this->info('Attachment completed');
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * @param MeetItem $item
     * @param Collection<int, string> $items
     * @return string
     */
    private function showItems(MeetItem $item, Collection $items): string
    {
        if ($items->contains($item->name)) {
            return sprintf('%s*', $item->name);
        }

        return $item->name;
    }
}
