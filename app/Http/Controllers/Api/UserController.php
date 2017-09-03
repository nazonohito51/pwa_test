<?php
namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\SuccessStatus;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'nickname' => ['required', 'string', 'max:255'],
        ]);

        $user->nickname = $request->get('nickname');
        $user->save();

        return new ApiResponse(new SuccessStatus(), 'updating user nickname is succeeded.', $user);
    }
}
