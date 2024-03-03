<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DocumentedResource
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
