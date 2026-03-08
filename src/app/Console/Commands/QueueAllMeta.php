<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dvd;
use App\Jobs\FillMeta;
use App\Models\JobStatus;

class QueueAllMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:queue-all-meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach(Dvd::get() as $dvd) {
            JobStatus::create([
                'type' => 'FillMeta',
                'reference_id' => $dvd->id,
                'status' => 'pending'
            ]);
        }
    }
}
