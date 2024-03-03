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
 * @property Collection|AttributeClassItem[] $items
 */
class BuildControllersFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['createControllers'] = 'controllers';
        }

        return $files;
    }

    protected function createControllers(): ?string
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $php = $this->phpHeader('controllers', 'elements', 'Controllers', 'Controllers');


        foreach ($this->items as $item) {
            /** @var ReflectionClass $ref */
            $ref = $item->ref;

            $parentMethods = collect($ref->getParentClass()->getMethods())->map->getName()->toArray();

            $php .= $this->markdown("## " . $item->ref->getName());
            $php .= $this->markdown($item->attribute->description);
            $methods = $item->class->getMethods();
            if ($methods) {
                $php .= $this->nl();
                $php .= $this->createBootstrapTable(
                    collect($methods)->map(function ($method) use ($item, $parentMethods) {
                        if ($method->class != $item->ref->getName() || in_array($method->getName(), $parentMethods)) {
                            return null;
                        }
                        return $method->getName();
                    })->filter()->toArray(),
                    ['Action name']
                );
            }
        }

        return $php;
    }
}
