<?php
namespace App\Services;

use App\DataAccess\Eloquent\User;
use Minishlink\WebPush\WebPush;

class SendPushNotificationsService
{
    private $webPush;

    /**
     * @var User
     */
    private $actor;

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

    public function setActor(User $actor)
    {
        $this->actor = $actor;
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

        $this->loggingParams($users, $message, $options, $type);

        foreach ($users as $user) {
            $this->sendPushNotification($user, $message, $options, $type);
        }

        $ret = $this->webPush->flush();

        if (is_array($ret)) {
            \Log::info('send push_notification result', [
                'ret' => var_export($ret, true)
            ]);
        } elseif ($ret === false) {
            \Log::error('sending push_notification is failed', [
                'notification message' => $message
            ]);
        } elseif ($ret === true) {
            \Log::info('sending push_notification is succeeded');
        }
    }

    private function sendPushNotification(User $user, $message, $options = [], $type)
    {
        if ($this->shouldSendUser($user, $type)) {
            foreach ($user->push_notifications as $notification) {
                $message_and_icon = json_encode(array_merge([
                    'message' => $message,
                    'icon' => $this->getIconUrl(),
                    'badge' => asset('images/badge.png')
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

    private function getIconUrl()
    {
        if ($this->actor && $this->actor->haveAvator()) {
            return $this->actor->avator_url;
        } else {
            return asset('images/icon.png');
        }
    }

    private function loggingParams($users, $message, $options, $type)
    {
        \Log::info('loggingParams', [
            'actor' => $this->actor,
            'users' => $users,
            'message' => $message,
            'options' => $options,
            'type' => $type
        ]);
    }
}
