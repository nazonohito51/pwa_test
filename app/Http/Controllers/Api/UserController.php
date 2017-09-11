<?php
namespace App\Http\Controllers\Api;

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

        $user->push_notification()->updateOrCreate([
            'endpoint' => $request->get('endpoint'),
            'key' => $request->get('key'),
            'token' => $request->get('token'),
        ]);

        return new ApiResponse(new SuccessStatus(), 'store user notification is succeeded');
    }
}
