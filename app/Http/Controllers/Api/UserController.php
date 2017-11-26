<?php
namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\PushNotification;
use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\BadRequest;
use App\Http\Response\ApiStatus\NotFoundStatus;
use App\Http\Response\ApiStatus\SuccessStatus;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        $user->load('user_setting');
        return new ApiResponse(new SuccessStatus(), 'getting user is succeeded', ['user' => $user]);
    }

    public function showByApiToken($api_token)
    {
        $user = User::where('api_token', '=', $api_token)->first();

        if ($user) {
            return new ApiResponse(new SuccessStatus(), 'getting user is succeeded', ['user' => $user]);
        } else {
            return new ApiResponse(new NotFoundStatus(), 'not found');
        }
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        $user->nickname = $request->get('nickname');
        $user->save();

        return new ApiResponse(new SuccessStatus(), 'updating user nickname is succeeded.', ['user' => $user]);
    }

    public function updateNotification(Request $request, User $user)
    {
        \Log::info('requested update notification parameters.', [
            'endpoint' => $request->get('endpoint'),
            'key' => $request->get('key'),
            'token' => $request->get('token'),
        ]);

        $this->validate($request, [
            'endpoint' => ['required', 'string', 'max:255'],
            'key' => ['string', 'max:255'],
            'token' => ['string', 'max:255'],
        ]);

        $push_notification = $user->push_notifications()->updateOrCreate([
            'endpoint' => $request->get('endpoint'),
            'key' => $request->get('key'),
            'token' => $request->get('token'),
        ]);

        return new ApiResponse(new SuccessStatus(), 'store user notification is succeeded', [
            'user' => $user,
            'push_notification' => $push_notification
        ]);
    }

    public function updateSetting(Request $request, User $user)
    {
        $this->validate($request, [
            'notification' => ['boolean'],
//            'post_article_notification' => ['boolean'],
//            'like_article_notification' => ['boolean'],
        ]);

        $user->user_setting()->update([
            'notification' => $request->get('notification'),
//            'post_article_notification' => $request->get('post_article_notification'),
//            'like_article_notification' => $request->get('like_article_notification'),
        ]);

        return new ApiResponse(new SuccessStatus(), 'updating user settings is succeeded');
    }

    public function getInterimUser(Request $request)
    {
        $query = User::where('role', '=', 'interim');

        \Log::info('endpoint', [
            'endpoint' => $request->get('endpoint')
        ]);

        if ($request->has('api_token')) {
            $query->where('api_token', '=', $request->get('api_token'));
        } else if ($request->has('endpoint')) {
            $endpoint = $request->get('endpoint');
            $query->whereHas('push_notifications', function ($query) use ($endpoint) {
                $query->where('endpoint', '=', $endpoint);
            });
        } else {
            return new ApiResponse(new BadRequest(), 'bad request');
        }

        if ($user = $query->first()) {
            return new ApiResponse(new SuccessStatus(), 'getting interim user is succeeded', ['user' => $user]);
        } else {
            return new ApiResponse(new NotFoundStatus(), 'not found');
        }
    }

    public function storeInterimUser(Request $request)
    {
        $this->validate($request, [
            'endpoint' => ['required', 'string', 'max:255'],
            'key' => ['string', 'max:255'],
            'token' => ['string', 'max:255'],
            'contentEncoding' => ['required']
        ]);

        if ($notification = PushNotification::where('endpoint', '=', $request->get('endpoint'))->first()) {
            $notification->update([
                'key' => $request->get('key'),
                'token' => $request->get('token'),
                'content_encoding' => $request->get('contentEncoding')
            ]);
            $user = $notification->user;
        } else {
            while (1) {
                $unique_name = str_random(30);
                if (is_null(User::where(['name' => $unique_name])->first())) {
                    break;
                }
            }

            $user = User::create([
                'name' => $unique_name,
                'nickname' => '名無しの誰かさん',
                'email' => $unique_name . '@test.com',
                'password' => bcrypt(str_random(60)),
                'role' => 'interim',
                'api_token' => str_random(60)
            ]);
            $user->push_notifications()->create([
                'endpoint' => $request->get('endpoint'),
                'key' => $request->get('key'),
                'token' => $request->get('token'),
                'content_encoding' => $request->get('contentEncoding')
            ]);
            $user->user_setting()->create();
        }

        return $user;
    }

    public function updateAvatar(Request $request, User $user)
    {
        $this->validate($request, [
            'image' => ['required']
        ]);

        $image_binary = base64_decode($request->get('image'));
        $user->avatar = $this->saveAvatarImage($user, $image_binary);
        $user->save();

        return new ApiResponse(new SuccessStatus(), 'uploading avatar is succeeded.');
    }

    /**
     * Originally, this should be defined User Model...
     * @param User $user
     * @param string $image_binary
     * @return string
     */
    private function saveAvatarImage(User $user, $image_binary)
    {
        $filename = $user->name . '.png';
        $file_path = '/images/avatars/' . $filename;
        file_put_contents(public_path() . $file_path, $image_binary);

        if (config('app.env') == 'production') {
            $ret = \Cloudinary\Uploader::upload(public_path() . $file_path);
            \Log::info('upload cloudinary.', [
                'ret' => $ret
            ]);

            return $ret['secure_url'];
        } else {
            return asset($file_path);
        }
    }
}
