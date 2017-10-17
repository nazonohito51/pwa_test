<?php
namespace App\Services;

use App\DataAccess\Eloquent\User;
use Minishlink\WebPush\WebPush;

class SendPushNotificationsService
{
    private $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => 'https://github.com/nazonhito51/pwa_test/',
                'publicKey' => 'BJbwhdyPzgvLnBmxYat8cGJSck_wy0Ph_vRTPHemglPtSrmiLZ1R05yFbnfQJen-MbS97RejCn3xm6Y4v1ZvZ1Q',
                'privateKey' => 'bOHQ5fmaaNfZiwsc2xhFFH8_iJVCsYbnfScaMPTuQQw',
            ],
        ]);
    }

    /**
     * @param User|User[] $users
     * @param string $message
     */
    public function execute($users, $message)
    {
        if ($users instanceof User) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $this->sendPushNotification($user, $message);
        }

        $this->webPush->flush();
    }

    private function sendPushNotification(User $user, $message)
    {
        foreach ($user->push_notifications as $notification) {
            \Log::info('notification', [
                'message' => $message,
                'endpoint' => $notification->endpoint,
                'key' => $notification->key,
                'token' => $notification->token
            ]);
            $this->webPush->sendNotification(
                $notification->endpoint,
                $message,
                $notification->key,
                $notification->token
            );
        }
    }
}
