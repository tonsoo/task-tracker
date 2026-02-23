<?php

namespace Tonsoo\TaskTracker;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Tonsoo\TaskTracker\AI\AiIntentAnalyzer;
use Tonsoo\TaskTracker\AI\Contracts\LLMClient;
use Tonsoo\TaskTracker\Console\Commands\MonitorIdleTranscripts;
use Tonsoo\TaskTracker\Contracts\AiDriver;
use Tonsoo\TaskTracker\Contracts\TaskDriver;
use Tonsoo\TaskTracker\Contracts\TaskManager;
use Tonsoo\TaskTracker\Jobs\ProcessMessageBatchJob;
use Tonsoo\TaskTracker\Messaging\Adapters\WhatsAppAdapter;
use Tonsoo\TaskTracker\Messaging\MessagingDriverRegistry;
use Tonsoo\TaskTracker\Models\IncomingMessage;
use Tonsoo\TaskTracker\Services\Task\TaskOrchestrator;
use Tonsoo\TaskTracker\Services\WhatsappService;
use Tonsoo\TaskTracker\UseCases\ProcessIncomingMessage;

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

        $this->app->singleton(AiDriver::class, function ($app) {
            $driverKey = config('task-tracker.ai.driver', 'openai');
            $driverConfig = config("task-tracker.ai.drivers.$driverKey", []);
            $driverClass = $driverConfig['driver'] ?? null;

            if (!$driverClass) {
                throw new \RuntimeException("AI driver [$driverKey] does not define a driver class.");
            }

            return $app->make($driverClass);
        });

        $this->app->singleton(LLMClient::class, function ($app) {
            $driverKey = config('task-tracker.ai.driver', 'openai');
            $driverConfig = config("task-tracker.ai.drivers.$driverKey", []);

            /** @var AiDriver $driver */
            $driver = $app->make(AiDriver::class);

            return $driver->makeClient($driverConfig);
        });

        $this->app->singleton(TaskDriver::class, function ($app) {
            $driverKey = config('task-tracker.task_driver', 'trello');
            $driverConfig = config("task-tracker.task_drivers.$driverKey", []);
            $driverClass = $driverConfig['driver'] ?? null;

            if (!$driverClass) {
                throw new \RuntimeException("Task driver [$driverKey] does not define a driver class.");
            }

            return $app->make($driverClass);
        });

        $this->app->singleton(TaskManager::class, function ($app) {
            $driverKey = config('task-tracker.task_driver', 'trello');
            $driverConfig = config("task-tracker.task_drivers.$driverKey", []);

            /** @var TaskDriver $driver */
            $driver = $app->make(TaskDriver::class);

            return $driver->makeManager($driverConfig);
        });

        $this->app->singleton(TaskOrchestrator::class, function ($app) {
            $driverKey = config('task-tracker.task_driver', 'trello');
            $driverConfig = config("task-tracker.task_drivers.$driverKey", []);

            /** @var TaskDriver $driver */
            $driver = $app->make(TaskDriver::class);

            return $driver->makeOrchestrator(
                config: $driverConfig,
                ai: $app->make(AiIntentAnalyzer::class),
            );
        });
        $this->app->singleton(WhatsappService::class);

        $this->app->singleton(AiIntentAnalyzer::class);
        $this->app->singleton(ProcessIncomingMessage::class);

        $this->app->singleton(WhatsAppAdapter::class);
        $this->app->singleton(MessagingDriverRegistry::class, function ($app) {
            return new MessagingDriverRegistry(
                container: $app,
                drivers: config('task-tracker.messaging.drivers', [])
            );
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
            __DIR__ . '/../config/task-tracker.php' => config_path('task-tracker.php'),
        ], 'task-tracker-config');
    }
}
