<?php

namespace Bfg\OpenDoc\Commands;

use Bfg\OpenDoc\Attributes\DocumentedModel;
use Exception;
use Illuminate\Console\Command;
use Bfg\Attributes\Items\AttributeClassItem;
use Bfg\Attributes\Attributes;
use JetBrains\PhpStorm\NoReturn;

class DocGenerateCommand extends Command
{
    protected $signature = 'doc:generate';

    protected $description = 'Command description';

    /**
     * @param  BuilderFactory  $factory
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function handle(BuilderFactory $factory): void
    {
        $factory->buildDefault();

        $factory->buildApis();

        $factory->buildScheduling();

        $factory->buildModels();

        $factory->buildControllers();

        $factory->buildCommands();

        $factory->buildCasts();

        $factory->buildChannels();

        $factory->buildComponents();

        $factory->buildEvents();

        $factory->buildExceptions();

        $factory->buildMails();

        $factory->buildMiddlewares();

        $factory->buildNotifications();

        $factory->buildObservers();

        $factory->buildPolicys();

        $factory->buildProviders();

        $factory->buildRepositories();

        $factory->buildRequests();

        $factory->buildResources();

        $factory->buildRules();

        $factory->buildScopes();

        $factory->buildTests();

        $factory->save();

        $this->comment('All done');
    }
}
