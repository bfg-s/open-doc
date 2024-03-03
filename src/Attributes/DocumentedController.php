<?php

namespace Bfg\OpenDoc\Attributes;

use Attribute;

/**
 * Class DocumentedModel
 * Attribute for documented model class in open doc.
 *
 * @package Bfg\OpenDoc\Attributes
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DocumentedController
{
    public function __construct(
        public ?string $description = null,
    ) {
    }
}
