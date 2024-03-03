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
use Illuminate\Support\Facades\Event;
use ReflectionClass;

/**
 * @property Collection|AttributeClassItem[] $items
 */
class BuildEventsFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['create'] = 'events';
        }

        return $files;
    }

    protected function create(): ?string
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $php = $this->phpHeader('events', 'elements', 'Events', 'Events');


        foreach ($this->items as $item) {
            $php .= $this->markdown("## " . $item->ref->getName());
            $php .= $this->markdown($item->attribute->description);
//            $listeners = Event::getListeners(class_basename($item->ref->getName()));
//            if ($listeners) {
//                $php .= $this->markdown("### Listeners");
//            }
//            dd($listeners, $item->ref->getName());
        }



        return $php;
    }
}
