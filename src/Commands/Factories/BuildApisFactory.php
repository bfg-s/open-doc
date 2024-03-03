<?php

namespace Bfg\OpenDoc\Commands\Factories;

use Artisan;
use Bfg\OpenDoc\Traits\ModelHelpers;
use Bfg\OpenDoc\Traits\UI;
use DOMException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Bfg\Attributes\Items\AttributeClassItem;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Class BuildModelFactory
 * @package Bfg\OpenDoc\Commands
 * @property Collection|ReflectionClass[] $items
 */
class BuildApisFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        foreach ($this->items as $index => $item) {

            $file = $this->fileByGroup($item->attribute->group);

            if (! in_array($file, $files)) {

                $files['createFile' . str_repeat('_', $index)] = $file;
            }
        }

        return $files;
    }

    protected function fileByGroup(string $group): ?string
    {
        $file = Str::snake($group, '_');

        return "api_" . $file;
    }

    /**
     * @throws DOMException
     */
    protected function createFile($group): ?string
    {
        $items = $this->items->filter(
            fn ($item) => $group == $this->fileByGroup($item->attribute->group)
        );

        $php = $this->phpHeader(
            $group,
            'api',
            $items->first()->attribute->group,
            $items->first()->attribute->group
        );

        $headers = ['Method', 'URI', 'Description'];

        $php .= $this->createBootstrapTable(
            $items->map(
                fn ($item) => [
                    $item->attribute->method,
                    $item->attribute->uri,
                    $item->attribute->description,
                ]
            )->toArray(),
            $headers
        );

        return $php;
    }
}
