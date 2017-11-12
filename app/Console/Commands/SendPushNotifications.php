<?php

namespace App\Console\Commands;

use App\DataAccess\Eloquent\User;
use App\Services\SendPushNotificationsService;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;

class SendPushNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:push_notification {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var SendPushNotificationsService
     */
    private $service;

    /**
     * Create a new command instance.
     *
     * @param SendPushNotificationsService $service
     * @return void
     */
    public function __construct(SendPushNotificationsService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($user_id = $this->argument('user_id')) {
            $user = User::find($user_id);
        } else {
            $user = User::all();
        }

        $this->service->execute($user, 'プッシュ通知だよー');
    }
}
