<?php

namespace Bfg\OpenDoc;

use Exception;

class OpenDoc {

    /**
     * The pages array of the documentation.
     *
     * @var array $pages
     */
    protected array $pages = [];

    /**
     * The current key of the pages array.
     *
     * @var int $current_key
     */
    protected int $current_key = 0;

    /**
     * Register a new page in the documentation.
     *
     * @param  string  $view
     * @param  array  $data
     * @return void
     */
    public function page(string $view, array $data = []): void
    {
        $this->pages[] = [
            'view' => $view,
            'data' => $data,
            'viewInstance' => null,
            'html' => null,
        ];
    }

    public function initPages(): array
    {
        foreach ($this->pages as $key => $page) {
            $this->current_key = $key;
            $viewInstance = view($page['view'], $page['data'] ?? []);
            $page['html'] = $viewInstance->render();
            $this->pages[$key] = array_merge($this->pages[$key], $page);
        }

        return $this->pages;
    }

    /**
     * @param  array  $settings
     * @return void
     * @throws Exception
     */
    public function init(array $settings = []): void
    {
        if (isset($this->pages[$this->current_key])) {
            if (! isset($settings['id'])) {
                throw new Exception('No id found for settings');
            }
            if (! isset($settings['zone'])) {
                throw new Exception('No zone found for settings! Must be "started", "elements" or "api"');
            }
            if (! isset($settings['name'])) {
                throw new Exception('No name found for settings');
            }
            if (! isset($settings['description']) || ! $settings['description']) {
                $settings['description'] = null;
            }
            $this->pages[$this->current_key] = array_merge(
                $this->pages[$this->current_key], $settings
            );
        } else {
            throw new Exception('No page found for settings');
        }
    }
}
