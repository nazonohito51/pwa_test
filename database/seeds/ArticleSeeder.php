<?php

use App\DataAccess\Eloquent\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->storeArticle($this->getArticleParams());
    }

    private function storeArticle(array $article_params)
    {
        foreach ($article_params as $user_name => $article_param) {
            $user = User::where('name', $user_name)->first();

            foreach ($article_param as $param) {
                $user->articles()->create($param);
            }
        }
    }

    private function getArticleParams()
    {
        return [
            'Symfony' => [
                [
                    'title' => 'Symfony使ってください',
                    'body' => '<p>コンポーネント単位でもいいんじゃよ</p>',
                    'published' => true,
                ]
            ],
            'FuelPHP' => [
                [
                    'title' => 'Fuel使ってください',
                    'body' => '<p>PHP5.3でも動きます</p>	',
                    'published' => true,
                ]
            ],
            'Slim' => [
                [
                    'title' => 'Slim使ってください',
                    'body' => '<p>めちゃ薄い</p>	',
                    'published' => true,
                ]
            ],
            'CodeIgniter' => [
                [
                    'title' => 'CodeIgniter使ってください',
                    'body' => '<p>ライセンス問題はもう解消されてます</p>',
                    'published' => true,
                ]
            ],
            'Laravel' => [
                [
                    'title' => 'Laravel使ってください',
                    'body' => '<p>名前の由来はナルニア王国物語</p>',
                    'published' => true,
                ]
            ],
            'Phalcon' => [
                [
                    'title' => 'Phalcon使ってください',
                    'body' => '<p>最速</p>',
                    'published' => true,
                ]
            ],
            'CakePHP' => [
                [
                    'title' => 'CakePHP使ってください',
                    'body' => '<p>設定よりも規約</p>',
                    'published' => true,
                ]
            ],
            'ZendFramework' => [
                [
                    'title' => 'ZendFramework使ってください',
                    'body' => '<p>まだまだ現役</p>',
                    'published' => true,
                ]
            ],
            'barie' => [
                [
                    'title' => 'テスト投稿',
                    'body' => '<p>ですよ</p>',
                    'published' => true,
                    'created_at' => Carbon::now()->subHour()
                ]
            ],
        ];
    }
}
