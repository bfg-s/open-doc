<?php

namespace Bfg\OpenDoc\Commands\Factories;

use Artisan;
use Bfg\OpenDoc\Traits\ModelHelpers;
use Bfg\OpenDoc\Traits\UI;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Bfg\Attributes\Items\AttributeClassItem;
use ReflectionClass;

/**
 * Class BuildModelFactory
 * @package Bfg\OpenDoc\Commands
 * @property Collection|ReflectionClass[] $items
 */
class BuildSchedulingFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['createSchedulingTable'] = 'scheduling';
        }

        return $files;
    }

    protected function createSchedulingTable(): ?string
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $php = $this->phpHeader('scheduling', 'elements', 'Scheduling', 'Scheduling');

        $headers = [
            'Task', 'Expression', 'Description', 'Readable'
        ];

        $data = $this->items->map(function (array $task) {
            return [
                $task['task'],
                $task['expression'],
                $task['description'],
                $task['readable']
            ];
        })->toArray();

        $php .= $this->createBootstrapTable($data, $headers);

        return $php;
    }
}
