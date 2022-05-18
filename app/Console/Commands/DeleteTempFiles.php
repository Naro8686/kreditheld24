<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DeleteTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear temporary storage';

    protected $lifeTime;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->lifeTime = config('filesystem.disks.tmp.lifetime', 20 * 60 * 60);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('ok');
        // Get all files from temporary storage
        $files = Storage::disk('tmp')->allFiles();

        // Get a Carbon instance for the current date and time.
        $now = Carbon::now();

        // Iterate through all files in directory
        foreach ($files as $file) {
            // Get a Carbon instance for the file modified date and time.
            $modTimestamp = Storage::disk('tmp')->lastModified($file);
            $modeDate = Carbon::createFromTimestamp($modTimestamp);

            // Calculate date difference
            $length = $modeDate->diffInSeconds($now);

            // Check if the file is old enough to delete it
            if ($length > $this->lifeTime) {
                Storage::disk('tmp')->delete($file);
            }
        }
        return 0;
    }
}
