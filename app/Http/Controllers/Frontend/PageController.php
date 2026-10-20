<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PageRepositoryInterface;

class PageController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function page($id_page)
    {
        $page = $this->pageRepository->find($id_page);

        if (!$page) {
            abort(404);
        }

        $data['page_detail'] = $page;

        return view('frontend.modules.page.index', $data);
    }
}
