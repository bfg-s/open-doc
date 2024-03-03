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
 * @property Collection|AttributeClassItem[] $items
 */
class BuildNotificationFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['create'] = 'notifications';
        }

        return $files;
    }

    protected function create(): ?string
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $php = $this->phpHeader('notifications', 'elements', 'Notifications', 'Notifications');


        foreach ($this->items as $item) {
            $php .= $this->markdown("## " . $item->ref->getName());
            $php .= $this->markdown($item->attribute->description);
        }

        return $php;
    }
}
