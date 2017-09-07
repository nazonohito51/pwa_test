<?php
namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\SuccessStatus;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request, User $user)
    {
        return new ApiResponse(new SuccessStatus(), 'getting user is succeeded', ['user' => $user]);
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

    public function storeNotification(Request $request, User $user)
    {
        $this->validate($request, [
            'endpoint' => ['required', 'string', 'max:255']
        ]);

        $user->notifications()->updateOrCreate([
            'endpoint' => $request->get('endpoint')
        ]);

        return new ApiResponse(new SuccessStatus(), 'store user notification is succeeded');
    }
}
