<?php

namespace Bfg\OpenDoc\Commands\Factories;

use Bfg\OpenDoc\Traits\ModelHelpers;
use Bfg\OpenDoc\Traits\UI;
use DOMException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Bfg\Attributes\Items\AttributeClassItem;
use Illuminate\Support\Facades\File;

/**
 * Class BuildModelFactory
 * @package Bfg\OpenDoc\Commands
 * @property Collection|AttributeClassItem[] $items
 */
class BuildModelFactory extends Factory
{
    use UI;
    use ModelHelpers;

    public function files(): array
    {
        $files = [];

        if ($this->items->isNotEmpty()) {
            $files['createModelDescriptions'] = 'model-descriptions';
        }

        return $files;
    }

    /**
     * @return string|null
     * @throws DOMException
     * @throws FileNotFoundException
     */
    protected function createModelDescriptions(): ?string
    {
        if ($this->items->isEmpty()) {

            return null;
        }

        $php = $this->phpHeader('model-description', 'elements', 'Models', 'Models');

        foreach ($this->items as $item) {
            /** @var Model $model */
            $model = new ($item->class->getName());
            if ($model instanceof Model) {

                $tabs = [];

                $php .= $this->markdown("## {$item->class->getName()} ({$model->getTable()})");

                $php .= $this->markdown($item->attribute->description ?? '');
                if ($model->getFillable()) {
                    $php .= $this->nl();
                    $headers = ['Field', 'Type'];
                    $rows = [];

                    if ($model->getKeyName()) {

                        $rows[] = [$model->getKeyName(), $model->getKeyType()];
                    }

                    foreach ($model->getFillable() as $field) {
                        $rows[] = [$field, $model->getCasts()[$field] ?? 'string'];
                    }

                    if ($model->timestamps) {

                        if ($model->getCreatedAtColumn()) {
                            $rows[] = [$model->getCreatedAtColumn(), 'datetime'];
                        }

                        if ($model->getUpdatedAtColumn()) {
                            $rows[] = [$model->getUpdatedAtColumn(), 'datetime'];
                        }

                        if (method_exists($model, 'getDeletedAtColumn') && $model->getDeletedAtColumn()) {
                            $rows[] = [$model->getDeletedAtColumn(), 'datetime'];
                        }

                    }

                    if (count($rows)) {
                        $tabs[] = [
                            'title' => "Fields",
                            'content' => $this->createBootstrapTable($rows, $headers),
                        ];
                    }
                }

                $relationHeaders = ['Name', 'Type', 'Model', 'Foreign Key'];
                $relations = $this->getModelRelations($model)->map(function (array $relation) use ($model) {
                    $relationInstance = $model->{$relation['name']}();
                    if ($relationInstance instanceof Relation) {
                        return [
                            $relation['name'],
                            $relation['type'],
                            get_class($relationInstance->getModel()),
                            $relationInstance->getModel()->getForeignKey(),
                        ];
                    }
                    return null;
                })->filter();

                if ($relations->isNotEmpty()) {
                    $tabs[] = [
                        'title' => "Relations",
                        'content' => $this->createBootstrapTable($relations->toArray(), $relationHeaders),
                    ];
                }

                $migrations = $this->findMigrationsForModel($item->class->getName());
                if (count($migrations)) {
                    $tabs[] = [
                        'title' => "Migrations",
                        'content' => $this->createBootstrapTable($migrations, ['Class']),
                    ];
                }

                $php .= $this->nl();
                $php .= $this->createBootstrapTabs($tabs);

                $factoryClass = "\\Database\\Factories\\" . class_basename($item->class->getName()) . "Factory";

                if (class_exists($factoryClass)) {
                    $php .= $this->nl();
                    $php .= $this->createAlertBox(
                        'The factory for this model exists.',
                        'The factory for this model exists. You can find it in the `Database\\Factories` directory.'
                    );
                }

                if (method_exists($model, 'getDeletedAtColumn')) {
                    $php .= $this->nl();
                    $php .= $this->createAlertBox(
                        'The soft delete for this model exists.',
                        'The soft delete for this model exists. You can find it in the `use SoftDeletes;` trait in the model.',
                        'danger'
                    );
                }
            }
        }


        return $php;
    }

    /**
     * Поиск файлов миграции, содержащих имя таблицы модели.
     *
     * @param  string  $modelName  Имя модели, для которой нужно найти миграции.
     * @return array Список файлов миграции, содержащих имя таблицы модели.
     * @throws FileNotFoundException
     */
    function findMigrationsForModel(string $modelName): array
    {
        // Получаем путь к папке с миграциями
        $migrationsPath = database_path('migrations');

        // Определяем имя таблицы из модели
        $modelInstance = app($modelName);
        $tableName = $modelInstance->getTable();

        // Получаем список всех файлов миграции
        $migrationFiles = File::files($migrationsPath);

        // Массив для хранения файлов миграции, содержащих имя таблицы
        $foundMigrations = [];

        // Регулярное выражение для поиска имени таблицы в файле миграции
        $pattern = "/Schema::.*(create|table)\(['\"]{$tableName}['\"],/";

        foreach ($migrationFiles as $file) {
            // Читаем содержимое файла
            $content = File::get($file->getPathname());

            // Проверяем, содержит ли файл имя таблицы
            if (preg_match($pattern, $content)) {
                $foundMigrations[] = $file->getFilename();
            }
        }

        return $foundMigrations;
    }

}
