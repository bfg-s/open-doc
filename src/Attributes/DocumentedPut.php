<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

/**
 * Class DocumentedModel
 * Attribute for documented model class in open doc.
 *
 * @package Bfg\OpenDoc\Attributes
 */
#[Attribute(Attribute::IS_REPEATABLE|Attribute::TARGET_ALL)]
class DocumentedPut
{
    public function __construct(
        public string $group,
        public string $uri,
        public ?string $description = null,
        public string $method = "PUT",
    ) {
    }
}
