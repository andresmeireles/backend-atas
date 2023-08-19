<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ActiveMinute;
use Illuminate\Console\Command;

class ChangeStatusOfExpireMinuteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-status-of-expire-minute-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check active minutes and change status of expired ones';

    public function __construct(
        private readonly ActiveMinute $activeMinute
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $expiredActiveMinutes = $this->activeMinute->getActiveExpiredMinutes();
        $expiredActiveMinutes->map(fn ($activeMinute) => $activeMinute->minute?->expire());
    }
}
