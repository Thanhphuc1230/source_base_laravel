<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\HomeService;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function home()
    {
        $data = $this->homeService->getHomeData();
        return view('frontend.modules.home.index', $data);
    }

    public function factory()
    {
        $about_factory = \App\Models\About::where('uuid', '8037faa4-c262-41d7-aed5-60a479531b4f')->first()
            ?? \App\Models\About::where('status', 1)->first();

        return view('frontend.modules.factory.index', compact('about_factory'));
    }
}
