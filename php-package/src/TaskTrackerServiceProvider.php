<?php

namespace Tonso\TaskTracker;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Stevenmaguire\Services\Trello\Client;
use Tonso\TaskTracker\AI\AiIntentAnalyzer;
use Tonso\TaskTracker\AI\Clients\OpenAILLMClient;
use Tonso\TaskTracker\AI\Contracts\LLMClient;
use Tonso\TaskTracker\Console\Commands\MonitorIdleTranscripts;
use Tonso\TaskTracker\Contracts\TaskManager;
use Tonso\TaskTracker\Jobs\ProcessMessageBatchJob;
use Tonso\TaskTracker\Messaging\Adapters\WhatsAppAdapter;
use Tonso\TaskTracker\Models\IncomingMessage;
use Tonso\TaskTracker\Services\Trello\TrelloService;
use Tonso\TaskTracker\Services\Task\TaskOrchestrator;
use Tonso\TaskTracker\Services\WhatsappService;
use Tonso\TaskTracker\UseCases\ProcessIncomingMessage;

class TaskTrackerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'task-tracker-migrations');

            $this->commands([
                MonitorIdleTranscripts::class,
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('task-tracker:monitor-idle')->everyThirtySeconds();
                $schedule->job(new ProcessMessageBatchJob())->everyThirtySeconds();
                $schedule->call(function () {
                    IncomingMessage::where('created_at', '<', now()->subDays(10))->delete();
                })->daily();
            });
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/task-tracker.php',
            'task-tracker'
        );

        $this->app->singleton(Client::class, function () {
            return new Client([
                'key'   => config('task-tracker.task_managers.trello.key'),
                'token' => config('task-tracker.task_managers.trello.token'),
            ]);
        });

        $this->app->singleton(LLMClient::class, function () {
            return new OpenAILLMClient(
                apiKey: config('task-tracker.ai.openai.key'),
                model: config('task-tracker.ai.openai.model', 'gpt-4.1-mini'),
            );
        });

        $this->app->singleton(TrelloService::class, function ($app) {
            return new TrelloService(
                client: $app->make(Client::class),
                boardId: config('task-tracker.task_managers.trello.board_id'),
                defaultListId: config('task-tracker.task_managers.trello.default_list_id'),
            );
        });

        $this->app->singleton(TaskManager::class, function ($app) {
            $managerKey = config('task-tracker.task_manager', 'trello');
            $managerConfig = config("task-tracker.task_managers.$managerKey", []);
            $driver = $managerConfig['driver'] ?? null;

            if (!$driver) {
                throw new \RuntimeException("Task manager [$managerKey] does not define a driver class.");
            }

            return $app->make($driver);
        });

        $this->app->singleton(TaskOrchestrator::class);
        $this->app->singleton(WhatsappService::class);

        $this->app->singleton(AiIntentAnalyzer::class);
        $this->app->singleton(ProcessIncomingMessage::class);

        $this->app->singleton(WhatsAppAdapter::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
            __DIR__ . '/../config/task-tracker.php' => config_path('task-tracker.php'),
        ], 'task-tracker-config');
    }
}
