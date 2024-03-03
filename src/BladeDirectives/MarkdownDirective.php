<?php

namespace Bfg\OpenDoc\BladeDirectives;

class MarkdownDirective
{
    /**
     * @param $expression
     * @return string
     */
    public static function directive($expression): string
    {
        return "<?php echo \Illuminate\Support\Str::markdown($expression) ?>";
    }
}
