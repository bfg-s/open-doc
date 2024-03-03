<?php

namespace Bfg\OpenDoc;

use Bfg\OpenDoc\Repositories\DocumentationRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param  DocumentationRepository  $repository
     * @return View
     */
    public function index(DocumentationRepository $repository): View
    {
        $repository->setPages(
            \Bfg\OpenDoc\Facades\OpenDoc::initPages()
        );

        return view('open-doc::page', [
            'repo' => $repository,
        ]);
    }
}
