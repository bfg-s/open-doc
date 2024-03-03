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
class BuildCommandsFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['createCommandsTable'] = 'commands';
        }

        return $files;
    }

    protected function createCommandsTable()
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $php = $this->phpHeader('commands', 'elements', 'Commands', 'Commands');

        foreach ($this->items as $item) {

            $class = new ($item->getName());

            if ($class instanceof Command) {

                $php .= $this->markdown("## {$class->getName()}");
                //$php .= $this->markdown("## {$item->getName()}");
                Artisan::call($class->getName(), ['--help' => true]);
                $php .= $this->createCard(
                    $this->pre(Artisan::output())
                );
            }

        }

        return $php;
    }
}
