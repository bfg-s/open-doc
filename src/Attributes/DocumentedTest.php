<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class DocumentedTest
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
