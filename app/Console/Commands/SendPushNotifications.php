<?php

namespace App\Console\Commands;

use App\DataAccess\Eloquent\User;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;

class SendPushNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:push_notification';

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
        $webPush = new WebPush();
        $users = User::all();

        foreach ($users as $user) {
            $this->info($user->name);

            if ($notification = $user->push_notification) {
                $this->info($notification->endpoint);
                $webPush->sendNotification($notification->endpoint, 'hello!');
            }
        }
        $webPush->flush();

        return 'ok';
    }
}
