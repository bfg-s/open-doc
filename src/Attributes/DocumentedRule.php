<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DocumentedRule
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
