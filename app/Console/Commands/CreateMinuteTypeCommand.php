<?php

namespace App\Console\Commands;

use App\Service\Meet\AddMeetType;
use Illuminate\Console\Command;

class CreateMinuteTypeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:create-meet-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a meet type';

    public function __construct(private readonly AddMeetType $addMeetType)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->ask('What is the name of the minute type?');
        if ($name === null) {
            $this->error('Please enter a name for the minute type.');
            return;
        }
        try {
            $this->addMeetType->addNew($name);
            $this->info('Successfully created minute type!');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
