<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MeetType;
use App\Models\Minute;
use Illuminate\Console\Command;

class MigrateMinuteSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:migrate-minute-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace schema to meet type id';

    public function __construct(private readonly Minute $minute, private readonly MeetType $meetType)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $minutes = $this->minute->all();
        $minutes->map(fn (Minute $m) => $this->update($m));
    }

    private function update(Minute $minute): Minute
    {
        $schema = $minute->schema;
        $id = $this->meetType->where('name', $schema)->firstOrFail()->id;
        $minute->meet_type_id = $id;
        $minute->save();
        return $minute;
    }
}
