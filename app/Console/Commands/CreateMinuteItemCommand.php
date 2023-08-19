<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MeetItem;
use App\Service\Meet\AddMeetItem;
use Illuminate\Console\Command;

class CreateMinuteItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:create-meet-item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new meet item that can be used by meet types.';

    public function __construct(private readonly AddMeetItem $addMeetItem)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->slugfy($this->getItemName());
        /** @var string */
        $type = $this->choice(
            'What is the type of the minute item?',
            MeetItem::TYPES,
            default: 0,
        );
        $confirm = $this->confirm(
            sprintf(
                'Are you sure you want to create a minute item named "%s" of type "%s"?',
                $name,
                $type
            ),
            true
        );
        if ($confirm) {
            try {
                $this->addMeetItem->addNew(['name' => $name, 'type' => $type]);
                $this->info('Successfully created minute item!');
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        }
    }

    private function getItemName(): string
    {
        $name = $this->ask('What is the name of the minute item?');
        if ($name === null) {
            $this->error('Please enter a name for the minute item.');
            return $this->getItemName();
        }
        return (string) $name;
    }

    public function slugfy(string $name): string
    {
        return strtolower(trim(str_replace(' ', '_', $name)));
    }
}
