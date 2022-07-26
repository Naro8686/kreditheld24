<?php

namespace App\Console\Commands;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Console\Command;
use Log;
use Throwable;

class CheckEndsProposalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proposal:check_ends';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Proposal::selectRaw("*, DATE_ADD(created_at, INTERVAL deadline MONTH) AS expired")
            ->where('notified_to_admin', 0)
            ->having('expired', '<=', now()->addMonths(6))
            ->having('expired', '>', now())
            ->chunkById(100, function ($proposals) {
                try {
                    $data = [];
                    foreach ($proposals as $proposal) {
                        if ($admin = User::admin()) {
                            $text = 'Заявка заканчивается';
                            $data['url'] = route('admin.proposals.edit', [$proposal->id]);
                            if ($admin->email) $admin->sendEmail('<h1 style="text-align: center">' . $text . '</h1>', $data);
                            $proposal->update(['notified_to_admin' => 1]);
                        }
                    }
                } catch (Throwable $throwable) {
                    Log::error("CheckEndsProposalCommand - {$throwable->getMessage()}");
                }
            });
        return 0;
    }
}
