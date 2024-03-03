<?php

namespace Bfg\OpenDoc\Commands\Factories;

use Bfg\OpenDoc\Commands\BuilderFactory;
use Illuminate\Support\Collection;

abstract class Factory
{
    /**
     * @param  Collection|null  $items
     */
    public function __construct(
        protected ?Collection $items = null,
    ) {
        if (! $this->items) {
            $this->items = collect();
        }
    }

    abstract public function files(): array;

    //abstract public function build(string $file): string;

    /**
     * @param ...$parameters
     * @return array
     */
    public static function process(...$parameters): array
    {
        $instance = new static(...$parameters);

        $files = $instance->files();

        $readyFiles = [];

        foreach ($files as $method => $file) {
            $short = $file;
            $file = resource_path("views".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."documentation".DIRECTORY_SEPARATOR.$file.".blade.php");
            $method = trim($method, '_');
            if (method_exists($instance, $method)) {

                $result = $instance->{$method}($short);
                if ($result) {
                    $dir = dirname($file);
                    if (! is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    file_put_contents($file, $result);
                    $readyFiles[] = trim(str_replace(
                        ['.blade.php', DIRECTORY_SEPARATOR],
                        ['', '.'],
                        str_replace(resource_path('views'), '', $file)), '.');
                }
            }
        }

        return $readyFiles;
    }
}
