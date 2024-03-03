<?php

namespace Bfg\OpenDoc\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

trait ModelHelpers
{
    /**
     * @param  Model  $model
     * @return Collection
     */
    function getModelRelations(Model $model): Collection
    {
        $relations = [];
        $class = new ReflectionClass($model);

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // Пропускаем магические методы и методы, принадлежащие базовому классу Model
            if ($method->class == get_class($model) && !str_starts_with($method->name, '__')) {
                try {
                    $start_line = $method->getStartLine() - 1;
                    $end_line = $method->getEndLine();
                    $length = $end_line - $start_line;

                    $source = implode("", array_slice(file($method->getFileName()), $start_line, $length));

                    // Проверяем, содержит ли метод вызов одного из методов связи
                    if (preg_match('/return \$this->(hasOne|hasMany|belongsTo|belongsToMany|morphTo|morphMany|morphToMany|morphedByMany)\(/', $source, $m)) {
                        $relations[] = [
                            'name' => $method->name,
                            'type' => $m[1],
                        ];
                    }
                } catch (ReflectionException $e) {
                    // Обработка исключений, возникших при рефлексии
                }
            }
        }

        return collect($relations);
    }
}
