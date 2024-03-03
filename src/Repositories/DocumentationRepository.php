<?php

namespace Bfg\OpenDoc\Repositories;

use Bfg\Repository\Repository;
use Illuminate\Support\Collection;

/**
 * Class DocumentationRepository
 * @package App\Repositories
 * @property string $title
 * @property Collection $pagesForStarted
 * @property Collection $pagesForElements
 * @property Collection $pagesForApi
 */
class DocumentationRepository extends Repository
{
    public Collection $pages;

    public function pagesForStarted()
    {
        return $this->pages->where('zone', 'started');
    }

    public function pagesForElements()
    {
        return $this->pages->where('zone', 'elements');
    }

    public function pagesForApi()
    {
        return $this->pages->where('zone', 'api');
    }

    /**
     * @param  array  $pages
     * @return void
     */
    public function setPages(array $pages): void
    {
        $this->pages = collect($pages);
    }

    public function title()
    {
        return config('app.name');
    }
}
