<?php

namespace Exam;

use Exam\Console\Commands\ExamReminderCommand;
use Exam\Models\Exam;
use Exam\Models\ExamUser;
use Exam\Models\Feedback;
use Exam\Models\Invitation;
use Exam\Models\Question;
use Exam\Policies\ExamPolicy;
use Exam\Policies\ExamUserPolicy;
use Exam\Policies\FeedbackPolicy;
use Exam\Policies\InvitationPolicy;
use Exam\Policies\QuestionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    protected $defer = false;

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * List of command which will be registered.
     *
     * @var array
     */
    protected $commands = [
        ExamReminderCommand::class,
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //  'App\Model' => 'App\Policies\ModelPolicy',
        Exam::class => ExamPolicy::class,
        ExamUser::class => ExamUserPolicy::class,
        Question::class => QuestionPolicy::class,
        Invitation::class => InvitationPolicy::class,
        Feedback::class => FeedbackPolicy::class,
    ];

    /**
     * ServiceProvider constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->router = $app->get('router');
    }


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->registerPolicies();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'exam');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'exam');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/exam.php', 'exam'
        );

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
            $this->publishes([
                __DIR__ . '/../config/exam.php' => config_path('exam.php'),
            ], 'exam-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/exam'),
            ], 'exam-views');

            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    /**
     * To register laracrud as first level command. E.g. laracrud:model.
     *
     * @return array
     */
    public function provides()
    {
        return ['exam'];
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        $configuredPolicy = config('exam.policies', []);

        foreach ($this->policies as $key => $value) {
            if (isset($configuredPolicy[$key])) {
                Gate::policy($key, $configuredPolicy[$key]);
            } else {
                Gate::policy($key, $value);
            }
        }
    }
}
