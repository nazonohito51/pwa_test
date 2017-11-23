<?php

namespace App\Console\Commands;

use App\DataAccess\Eloquent\Job;
use App\DataAccess\Eloquent\User;
use App\Services\SendPushNotificationsService;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;

class CheckJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $headers = ['id', 'queue', 'payload', 'attempt', 'reserved_at', 'available_at', 'created_at'];
        $jobs = Job::all($headers)->toArray();
        $this->table($headers, $jobs);
    }
}
