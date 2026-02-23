# Getting Started

This guide gets you from zero to a working installation with Trello + WhatsApp.

## Prerequisites
- Laravel app with queue configured
- A public HTTPS endpoint for webhooks
- Trello and WhatsApp credentials

## Steps
1. Install the package
```bash
composer require tonsoo/task-tracker
```

2. Publish config
```bash
php artisan vendor:publish --tag=task-tracker-config
```

3. Run migrations
```bash
php artisan migrate
```

4. Configure `.env`
See [Configuration](docs/concepts/configuration.md).

5. Start the queue worker
```bash
php artisan queue:work
```

6. Ensure the scheduler runs
Configure your system scheduler to run:
```bash
php artisan schedule:run
```

7. Set up WhatsApp webhooks
See [WhatsApp Cloud API Setup](docs/integrations/whatsapp-setup.md).

## Next
- [Usage](docs/concepts/usage.md)
- [Trello Integration](docs/drivers/trello.md)
