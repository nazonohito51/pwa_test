<?php
namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\PushNotification;
use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\NotFoundStatus;
use App\Http\Response\ApiStatus\SuccessStatus;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        return new ApiResponse(new SuccessStatus(), 'getting user is succeeded', ['user' => $user]);
    }

    public function showByApiToken($api_token)
    {
        $user = User::where('api_token', '=', $api_token);

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
                $unique_name = str_random(60);
                if (is_null(User::where(['name' => $unique_name])->first())) {
                    break;
                }
            }

            $user = User::create([
                'name' => $unique_name,
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
        }

        return $user;
    }
}
