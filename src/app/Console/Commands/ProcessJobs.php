<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobStatus;
use App\Helpers\MetaHandler;

class ProcessJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-jobs';

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
        $keep = JobStatus::where('status', '<>', 'pending')
            ->orderByDesc('id')
            ->limit(500)
            ->pluck('id');

        JobStatus::where('status', '<>', 'pending')
            ->whereNotIn('id', $keep)
            ->delete();

        $jobs = JobStatus::where('status', 'pending')
            ->limit(25)
            ->get();

        foreach ($jobs as $job) {

            $job->update([
                'status' => 'running',
                'started_at' => now()
            ]);

             try {

                if ($job->type === 'FillMeta') {
                    MetaHandler::run($job->reference_id);

                    $job->update([
                        'status' => 'completed',
                        'finished_at' => now()
                    ]);
                    continue;
                }



                $job->update([
                    'status' => 'failed',
                    'finished_at' => now(),
                    'error' => 'Type not matched',
                ]);

            } catch (\Throwable $e) {

                $job->update([
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'finished_at' => now()
                ]);

            }

            sleep(2);
        }

    }
}
