<?php

namespace App\Jobs;

use App\DataAccess\Eloquent\User;
use App\Services\SendPushNotificationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $actor;
    private $users;
    private $message;
    private $options;
    private $type;

    /**
     * Create a new job instance.
     *
     * @param User $actor
     * @param User[] $users
     * @param string $message
     * @param array $options
     * @param string $type
     */
    public function __construct(User $actor, $users, $message, $options = [], $type = null)
    {
        if ($users instanceof User) {
            $users = [$users];
        }

        $this->actor = $actor;
        $this->users = $users;
        $this->message = $message;
        $this->options = $options;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @param SendPushNotificationsService $service
     * @return void
     */
    public function handle(SendPushNotificationsService $service)
    {
        $service->setActor($this->actor);
        $service->execute($this->users, $this->message, $this->options, $this->type);
    }
}
