<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

/**
 * Class DocumentedCast
 * Attribute for documented cast class in open doc.
 *
 * @package Bfg\OpenDoc\Attributes
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DocumentedCast
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
