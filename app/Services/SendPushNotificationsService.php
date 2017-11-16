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
     * @param array $options
     * @param string $type
     */
    public function execute($users, $message, $options = [], $type = null)
    {
        if ($users instanceof User) {
            $users = [$users];
        }

        foreach ($users as $user) {
            $this->sendPushNotification($user, $message, $options, $type);
        }

        $this->webPush->flush();
    }

    private function sendPushNotification(User $user, $message, $options = [], $type)
    {
        if ($this->shouldSendUser($user, $type)) {
            foreach ($user->push_notifications as $notification) {
                $message_and_icon = json_encode(array_merge([
                    'message' => $message,
                    'icon' => $this->getIconUrl($user)
                ], $options));
                \Log::info('notification', [
                    'message' => $message_and_icon,
                    'endpoint' => $notification->endpoint,
                    'key' => $notification->key,
                    'token' => $notification->token
                ]);
                $this->webPush->sendNotification(
                    $notification->endpoint,
                    $message_and_icon,
                    $notification->key,
                    $notification->token
                );
            }
        }
    }

    private function shouldSendUser(User $user, $type)
    {
        return $user->user_setting->notification ? true : false;

//        if (is_null($type)) {
//            return true;
//        } elseif (is_string($type)) {
//            return $user->user_setting->getAttributeValue($type);
//        }
    }

    private function getIconUrl(User $user)
    {
        if ($user->haveAvator()) {
            return $user->avator_url;
        } else {
            return asset('images/icon.png');
        }
    }
}
