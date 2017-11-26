<?php

namespace App\Providers;

use Cloudinary;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment() == 'production') {
            $account = parse_url(getenv('CLOUDINARY_URL'));
            Cloudinary::config([
                'cloud_name' => $account['host'],
                'api_key' => $account['user'],
                'api_secret' => $account['pass']
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
