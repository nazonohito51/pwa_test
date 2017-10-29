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
            'post_article_notification' => ['boolean'],
            'like_article_notification' => ['boolean'],
        ]);

        $user->user_setting()->update([
            'post_article_notification' => $request->get('post_article_notification'),
            'like_article_notification' => $request->get('like_article_notification'),
        ]);

        return new ApiResponse(new SuccessStatus(), 'updating user settings is succeeded');
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

    public function updateAvator(Request $request, User $user)
    {
        $this->validate($request, [
            'image' => ['required']
        ]);

        $image_binary = base64_decode($request->get('image'));
        $filename = $user->name . '.png';
        file_put_contents(public_path() . '/images/avators/' . $filename, $image_binary);

        $user->avator = $filename;
        $user->save();

        return new ApiResponse(new SuccessStatus(), 'uploading avator is succeeded.');
    }
}
