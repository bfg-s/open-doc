<?php

namespace Bfg\OpenDoc\Commands\Helpers;

use Exception;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class Scheduling
{
    /**
     * @var string out put file for command.
     */
    protected string $sendOutputTo;

    /**
     * Get all events in console kernel.
     *
     * @return array
     * @throws BindingResolutionException
     */
    protected function getKernelEvents(): array
    {
        app()->make('Illuminate\Contracts\Console\Kernel');

        return app()->make('Illuminate\Console\Scheduling\Schedule')->events();
    }

    /**
     * Get all formatted tasks.
     *
     * @return Collection
     * @throws Exception
     */
    public function getTasks(): Collection
    {
        $tasks = [];

        foreach ($this->getKernelEvents() as $event) {
            $tasks[] = [
                'task'          => $this->formatTask($event),
                'expression'    => $event->expression,
                'nextRunDate'   => $event->nextRunDate()->format('Y-m-d H:i:s'),
                'description'   => $event->description,
                'readable'      => CronSchedule::fromCronString($event->expression)->asNaturalLanguage(),
            ];
        }

        return collect($tasks);
    }

    /**
     * Format a giving task.
     *
     * @param $event
     *
     * @return string
     */
    protected function formatTask($event): string
    {
        if ($event instanceof CallbackEvent) {
            return 'Closure';
        }

        if (Str::contains($event->command, '\'artisan\'')) {
            $exploded = explode(' ', $event->command);

            return 'artisan '.implode(' ', array_slice($exploded, 2));
        }

        if (PHP_OS_FAMILY === 'Windows' && Str::contains($event->command, '"artisan"')) {
            $exploded = explode(' ', $event->command);

            return 'artisan '.implode(' ', array_slice($exploded, 2));
        }

        return $event->command;
    }

    /**
     * Run specific task.
     *
     * @param  int  $id
     *
     * @return string
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function runTask(int $id): string
    {
        set_time_limit(0);

        /** @var \Illuminate\Console\Scheduling\Event $event */
        $event = $this->getKernelEvents()[$id - 1];

        if (PHP_OS_FAMILY === 'Windows') {
            $event->command = Str::of($event->command)->replace('php-cgi.exe', 'php.exe');
        }

        $event->sendOutputTo($this->getOutputTo());

        $event->run(app());

        return $this->readOutput();
    }

    /**
     * @return string
     */
    protected function getOutputTo(): string
    {
        if (!$this->sendOutputTo) {
            $this->sendOutputTo = storage_path('app/task-schedule.output');
        }

        if (! is_file($this->sendOutputTo)) {
            file_put_contents($this->sendOutputTo, '');
        }

        return $this->sendOutputTo;
    }

    /**
     * Read output info from output file.
     *
     * @return string
     */
    protected function readOutput(): string
    {
        return file_get_contents($this->getOutputTo());
    }
}
