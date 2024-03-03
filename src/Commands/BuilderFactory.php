<?php

namespace Bfg\OpenDoc\Commands;

use App\Providers\OpenDocumentationProvider;
use Bfg\Attributes\Attributes;
use Bfg\Comcode\Subjects\ClassSubject;
use Bfg\Comcode\Subjects\DocSubject;
use Bfg\OpenDoc\Attributes\DocumentedCast;
use Bfg\OpenDoc\Attributes\DocumentedChannel;
use Bfg\OpenDoc\Attributes\DocumentedComponent;
use Bfg\OpenDoc\Attributes\DocumentedController;
use Bfg\OpenDoc\Attributes\DocumentedDelete;
use Bfg\OpenDoc\Attributes\DocumentedEvent;
use Bfg\OpenDoc\Attributes\DocumentedException;
use Bfg\OpenDoc\Attributes\DocumentedGet;
use Bfg\OpenDoc\Attributes\DocumentedJob;
use Bfg\OpenDoc\Attributes\DocumentedMail;
use Bfg\OpenDoc\Attributes\DocumentedMiddleware;
use Bfg\OpenDoc\Attributes\DocumentedModel;
use Bfg\OpenDoc\Attributes\DocumentedNotification;
use Bfg\OpenDoc\Attributes\DocumentedObserver;
use Bfg\OpenDoc\Attributes\DocumentedPolicy;
use Bfg\OpenDoc\Attributes\DocumentedPost;
use Bfg\OpenDoc\Attributes\DocumentedProvider;
use Bfg\OpenDoc\Attributes\DocumentedPut;
use Bfg\OpenDoc\Attributes\DocumentedRepository;
use Bfg\OpenDoc\Attributes\DocumentedRequest;
use Bfg\OpenDoc\Attributes\DocumentedResource;
use Bfg\OpenDoc\Attributes\DocumentedRule;
use Bfg\OpenDoc\Attributes\DocumentedScope;
use Bfg\OpenDoc\Attributes\DocumentedSeeder;
use Bfg\OpenDoc\Attributes\DocumentedTest;
use Bfg\OpenDoc\Commands\Factories\BuildApisFactory;
use Bfg\OpenDoc\Commands\Factories\BuildCastsFactory;
use Bfg\OpenDoc\Commands\Factories\BuildChannelFactory;
use Bfg\OpenDoc\Commands\Factories\BuildCommandsFactory;
use Bfg\OpenDoc\Commands\Factories\BuildComponentFactory;
use Bfg\OpenDoc\Commands\Factories\BuildControllersFactory;
use Bfg\OpenDoc\Commands\Factories\BuildDefaultsFactory;
use Bfg\OpenDoc\Commands\Factories\BuildEventsFactory;
use Bfg\OpenDoc\Commands\Factories\BuildJobFactory;
use Bfg\OpenDoc\Commands\Factories\BuildMailFactory;
use Bfg\OpenDoc\Commands\Factories\BuildMiddlewareFactory;
use Bfg\OpenDoc\Commands\Factories\BuildModelFactory;
use Bfg\OpenDoc\Commands\Factories\BuildNotificationFactory;
use Bfg\OpenDoc\Commands\Factories\BuildObserverFactory;
use Bfg\OpenDoc\Commands\Factories\BuildPolicyFactory;
use Bfg\OpenDoc\Commands\Factories\BuildProviderFactory;
use Bfg\OpenDoc\Commands\Factories\BuildRepositoryFactory;
use Bfg\OpenDoc\Commands\Factories\BuildRequestFactory;
use Bfg\OpenDoc\Commands\Factories\BuildResourceFactory;
use Bfg\OpenDoc\Commands\Factories\BuildRuleFactory;
use Bfg\OpenDoc\Commands\Factories\BuildSchedulingFactory;
use Bfg\OpenDoc\Commands\Factories\BuildScopeFactory;
use Bfg\OpenDoc\Commands\Factories\BuildSeederFactory;
use Bfg\OpenDoc\Commands\Factories\BuildTestFactory;
use Bfg\OpenDoc\Commands\Helpers\Scheduling;
use Bfg\OpenDoc\Facades\OpenDoc;
use ErrorException;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class BuilderFactory
{
    /**
     * The provider instance.
     *
     * @var ClassSubject
     */
    protected ClassSubject $provider;

    /**
     * @var Collection
     */
    protected Collection $pages;

    /**
     * @throws ErrorException
     */
    public function __construct()
    {
        $this->pages = collect();
        $this->provider = php()->class(OpenDocumentationProvider::class);
    }

    public function buildDefault(): void
    {
        $this->pages = $this->pages->merge(
            BuildDefaultsFactory::process()
        );
    }

    /**
     * @return void
     */
    public function buildModels(): void
    {
        $this->pages = $this->pages->merge(
            BuildModelFactory::process(
                Attributes::new(DocumentedModel::class)
                    ->wherePath(app_path())
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function buildScheduling(): void
    {
        $this->pages = $this->pages->merge(
            BuildSchedulingFactory::process(
                (new Scheduling())->getTasks()
            )
        );
    }

    /**
     * @return void
     */
    public function buildControllers(): void
    {
        $this->pages = $this->pages->merge(
            BuildControllersFactory::process(
                Attributes::new(DocumentedController::class)
                    ->wherePath(app_path())
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildCommands(): void
    {
        $this->pages = $this->pages->merge(
            BuildCommandsFactory::process(
                Attributes::new()
                    ->wherePath(app_path('Console/Commands'))
                    ->classes(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildCasts(): void
    {
        $this->pages = $this->pages->merge(
            BuildCastsFactory::process(
                Attributes::new(DocumentedCast::class)
                    ->wherePath(app_path('Casts'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildChannels(): void
    {
        $this->pages = $this->pages->merge(
            BuildChannelFactory::process(
                Attributes::new(DocumentedChannel::class)
                    ->wherePath(app_path('Broadcasting'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildComponents(): void
    {
        $this->pages = $this->pages->merge(
            BuildComponentFactory::process(
                Attributes::new(DocumentedComponent::class)
                    ->wherePath(app_path('View/Components'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildEvents(): void
    {
        $this->pages = $this->pages->merge(
            BuildEventsFactory::process(
                Attributes::new(DocumentedEvent::class)
                    ->wherePath(app_path('Events'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildExceptions(): void
    {
        $this->pages = $this->pages->merge(
            BuildEventsFactory::process(
                Attributes::new(DocumentedException::class)
                    ->wherePath(app_path('Exceptions'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildJobs(): void
    {
        $this->pages = $this->pages->merge(
            BuildJobFactory::process(
                Attributes::new(DocumentedJob::class)
                    ->wherePath(app_path('Jobs'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildMails(): void
    {
        $this->pages = $this->pages->merge(
            BuildMailFactory::process(
                Attributes::new(DocumentedMail::class)
                    ->wherePath(app_path('Mail'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildMiddlewares(): void
    {
        $this->pages = $this->pages->merge(
            BuildMiddlewareFactory::process(
                Attributes::new(DocumentedMiddleware::class)
                    ->wherePath(app_path('Http/Middleware'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildNotifications(): void
    {
        $this->pages = $this->pages->merge(
            BuildNotificationFactory::process(
                Attributes::new(DocumentedNotification::class)
                    ->wherePath(app_path('Notifications'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildObservers(): void
    {
        $this->pages = $this->pages->merge(
            BuildObserverFactory::process(
                Attributes::new(DocumentedObserver::class)
                    ->wherePath(app_path('Observers'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildPolicys(): void
    {
        $this->pages = $this->pages->merge(
            BuildPolicyFactory::process(
                Attributes::new(DocumentedPolicy::class)
                    ->wherePath(app_path('Policies'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildProviders(): void
    {
        $this->pages = $this->pages->merge(
            BuildProviderFactory::process(
                Attributes::new(DocumentedProvider::class)
                    ->wherePath(app_path('Providers'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildRepositories(): void
    {
        $this->pages = $this->pages->merge(
            BuildRepositoryFactory::process(
                Attributes::new(DocumentedRepository::class)
                    ->wherePath(app_path('Repositories'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildRequests(): void
    {
        $this->pages = $this->pages->merge(
            BuildRequestFactory::process(
                Attributes::new(DocumentedRequest::class)
                    ->wherePath(app_path('Http/Requests'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildResources(): void
    {
        $this->pages = $this->pages->merge(
            BuildResourceFactory::process(
                Attributes::new(DocumentedResource::class)
                    ->wherePath(app_path('Http/Resources'))
                    ->whereTargetClass()
                    ->all()
                    ->merge(
                        Attributes::new(DocumentedResource::class)
                            ->wherePath(app_path('Resources'))
                            ->whereTargetClass()
                            ->all()
                    ),
            )
        );
    }

    /**
     * @return void
     */
    public function buildRules(): void
    {
        $this->pages = $this->pages->merge(
            BuildRuleFactory::process(
                Attributes::new(DocumentedRule::class)
                    ->wherePath(app_path('Rules'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildScopes(): void
    {
        $this->pages = $this->pages->merge(
            BuildScopeFactory::process(
                Attributes::new(DocumentedScope::class)
                    ->wherePath(app_path('Scopes'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildSeeders(): void
    {
        $this->pages = $this->pages->merge(
            BuildSeederFactory::process(
                Attributes::new(DocumentedSeeder::class)
                    ->wherePath(database_path('seeders'))
                    ->whereTargetClass()
                    ->all(),
            )
        );
    }

    /**
     * @return void
     */
    public function buildTests(): void
    {
        $this->pages = $this->pages->merge(
            BuildTestFactory::process(
                Attributes::new(DocumentedTest::class)
                    ->wherePath(base_path('tests'))
                    ->whereTargetMethod()
                    ->all(),
            )
        );
    }

    public function buildApis(): void
    {
        $this->pages = $this->pages->merge(
            BuildApisFactory::process(
                Attributes::new(DocumentedGet::class)
                    ->wherePath(app_path())
                    ->all()->merge(
                        Attributes::new(DocumentedPost::class)
                            ->wherePath(app_path())
                            ->all()->merge(
                                Attributes::new(DocumentedDelete::class)
                                    ->wherePath(app_path())
                                    ->all()->merge(
                                        Attributes::new(DocumentedPut::class)
                                            ->wherePath(app_path())
                                            ->all(),
                                    ),
                            ),
                    ),
            )
        );
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $this->provider->use(OpenDoc::class);
        $this->provider->use(ServiceProvider::class);
        $this->provider->extends(ServiceProvider::class);
        $this->provider->forgetMethod('boot');
        $this->provider->publicMethod('register');
        $bootMethod = $this->provider->publicMethod('boot');
        $bootMethod->comment(function (DocSubject $doc) {
            $doc->name('Bootstrap services.');
            $doc->tagReturn('void');
        });

        foreach ($this->pages as $page) {

            $bootMethod->line()->staticCall('OpenDoc', 'page', $page);
        }

        $this->provider?->save();
    }
}
