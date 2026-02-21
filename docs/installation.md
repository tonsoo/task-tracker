# Installation

Short path: install the package, publish config, run migrations, and ensure your queue and scheduler are running.

## Prerequisites
- Laravel app with queue configured
- A public HTTPS endpoint for webhooks
- Trello and WhatsApp credentials if using the defaults

## Steps
1. Require the package
```bash
composer require tonso/task-tracker
```

2. Publish configuration
```bash
php artisan vendor:publish --tag=task-tracker-config
```

3. Run migrations
```bash
php artisan migrate
```

4. Configure `.env`
See [Configuration](configuration.md).

5. Start the queue worker
```bash
php artisan queue:work
```

6. Start the scheduler
Use your system scheduler to run:
```bash
php artisan schedule:run
```

7. Configure WhatsApp webhooks
See [WhatsApp Cloud API Setup](whatsapp-setup.md).

## Next
- [Usage](usage.md)
