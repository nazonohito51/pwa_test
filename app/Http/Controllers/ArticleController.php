<?php

namespace App\Http\Controllers;

use App\DataAccess\Eloquent\Article;
use App\DataAccess\Eloquent\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(User $user)
    {
        foreach ($user->articles as $article) {
            var_dump($article);
        }
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
            'body' => ['required', 'string', 'max:65535'],
        ]);

        $user->articles()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'published' => true,
        ]);

        return redirect()->route('articles.index', ['user' => $user->id]);
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
        
    }

    public function update(User $user, Article $article)
    {
        
    }

    public function destroy(User $user, Article $article)
    {

    }
}
