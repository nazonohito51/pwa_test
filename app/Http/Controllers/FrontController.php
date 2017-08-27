<?php
namespace App\Http\Controllers;

class FrontController
{
    public function vue()
    {
        return view('front.vue');
    }

    public function onsen()
    {
        return view('front.onsen');
    }

    public function onsenVue()
    {
        return view('front.onsenVue');
    }
}
