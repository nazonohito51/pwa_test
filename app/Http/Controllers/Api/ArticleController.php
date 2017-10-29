<?php

namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\Article;
use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\SuccessStatus;
use App\Services\SendPushNotificationsService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function all()
    {
        $articles = Article::with(['user', 'likeUsers'])->orderBy('created_at', 'desc')->limit(100)->get();
        return new ApiResponse(new SuccessStatus(), 'getting articles is succeeded.', ['articles' => $articles]);
    }

    public function index(User $user)
    {
        return $user->articles->toJson();
    }

    public function create(User $user)
    {
        return view('articles.create', [
            'user' => $user
        ]);
    }

    public function store(Request $request, SendPushNotificationsService $service, User $user)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:65535'],
        ]);

        $user->articles()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'published' => true,
        ]);

        $service->execute(User::all(), $user->nickname . 'さんが投稿しました');

        return redirect()->route('articles.index', ['user' => $user->name]);
    }

    public function show(User $user, Article $article)
    {
        var_dump($user->name);
        var_dump($article->title);
        var_dump($article->body);
        var_dump($article->created_at);
        var_dump($article->updated_at);
    }

    public function edit(User $user, Article $article)
    {
        return view('articles.edit', [
            'user' => $user,
            'article' => $article
        ]);
    }

    public function update(Request $request, User $user, Article $article)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:65535'],
        ]);

        $article->title = $request->get('title');
        $article->body = $request->get('body');
        $article->save();

        return redirect()->route('articles.index', ['user' => $user->name]);
    }

    public function destroy(User $user, Article $article)
    {
        if ($article->delete()) {
            return redirect()->route('articles.index', ['user' => $user->name]);
        }

        return redirect()->back()->with('summary', 'deleting record is failed.');
    }
}
