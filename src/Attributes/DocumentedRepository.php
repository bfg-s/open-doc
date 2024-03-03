<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DocumentedRepository
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
