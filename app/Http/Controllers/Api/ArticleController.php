<?php

namespace App\Http\Controllers\Api;

use App\DataAccess\Eloquent\Article;
use App\DataAccess\Eloquent\User;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Http\Response\ApiStatus\SuccessStatus;
use App\Jobs\SendPushNotificationJob;
use App\Services\SendPushNotificationsService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function all()
    {
        $articles = Article::with(['user'])->orderBy('created_at', 'desc')->limit(100)->get();
        return new ApiResponse(new SuccessStatus(), 'getting articles is succeeded.', ['articles' => $articles->makeHidden('body')]);
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

    public function store(Request $request, User $user)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:16777215'],
        ]);

        $article = $user->articles()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'published' => true,
        ]);

        SendPushNotificationJob::dispatch(
            User::all(),
            $user->nickname . 'が投稿しました',
            ['fetch_uri' => route('api.articles.show', ['article' => $article->id])],
            'post_article_notification'
        );

        return redirect()->route('articles.index', ['user' => $user->name]);
    }

    public function show(Article $article)
    {
        $article->load('user');
        return new ApiResponse(new SuccessStatus(), 'getting article is succeeded.', ['article' => $article]);
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

    public function like(Article $article)
    {
        $users = $article->likeUsers;
        return new ApiResponse(new SuccessStatus(), 'getting like users is succeeded.', ['users' => $users]);
    }

    public function storeLike(Article $article, Request $request)
    {
        $user = User::where('api_token', '=', $request->get('api_token'))->first();
        $article->likes()->firstOrCreate([
            'user_id' => $user->id
        ]);

        SendPushNotificationJob::dispatch(
            $article->user,
            $user->nickname . 'が記事にいいね！をしました',
            [],
            'like_article_notification'
        );

        return new ApiResponse(new SuccessStatus(), 'posting like is succeeded.');
    }
}
