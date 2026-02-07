## BaseLinker Integration Module

Integration module for helpdesk systems that connects with BaseLinker API to fetch orders from multiple marketplaces (Allegro, Amazon, etc.).

## Examples of usage 

### Fetch orders via CLI

```bash
# Fetch orders from Allegro
php bin/console app:order:fetch allegro

# Fetch orders from Amazon
php bin/console app:order:fetch amazon

# List available marketplaces
php bin/console app:order:fetch --list
```

### Fetch orders via HTTP API

```bash
# POST request to trigger order fetching
curl -X POST http://localhost/api/fetch-orders/allegro

# Response format
{
  "status": "success",
  "message": "Orders from \"allegro\" have been fetched."
}
```

### Programmatic usage

```php
use App\Order\Application\Command\Sync\FetchOrders;
use App\Shared\Application\Command\Sync\CommandBus;

// Inject CommandBus via dependency injection
$orders = $commandBus->dispatch(new FetchOrders('allegro'));

// Or use the handler directly
use App\Order\Application\Command\Sync\FetchOrdersHandler;

$handler = $container->get(FetchOrdersHandler::class);
$orders = $handler(new FetchOrders('allegro'));
```

## Adding a New Marketplace

Adding support for a new marketplace

### Step 1: Create marketplace class

```php
<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Marketplace;

readonly class EbayMarketplace extends AbstractMarketplace
{
    private const string SOURCE_NAME = 'ebay';

    public function getSourceName(): string
    {
        return self::SOURCE_NAME;
    }

    // Optional: Override fetchOrders() for custom logic
}
```

##  Monitoring & Logging

### Log Files (dev environment)

- `var/log/dev.log` - General application logs
- `var/log/baselinker_api.log` - BaseLinker API errors and requests
- `var/log/baselinker_performance.log` - Performance metrics of api (duration, memory)

### Performance Monitoring

The `StopwatchBaseLinkerClient` decorator automatically logs:
- Request duration (ms)
- Memory usage (bytes)
- API method called

Example log entry:
```json
{
  "message": "BaseLinker API call: getOrders took 245ms",
  "context": {
    "method": "getOrders",
    "duration": 245,
    "memory": 2097152
  }
}
```

**Note**: The decorator pattern used for BaseLinker API client can be applied to other components like command handlers, repositories, or services to monitor their performance as well.

### Messenger Configuration

The project uses Symfony Messenger for command handling with support for both synchronous and asynchronous processing.

#### Current Configuration

**Synchronous Bus**
- **Bus**: `command.sync.bus` (default)
- **Transport**: `sync://`
- **Interfaces**: `App\Shared\Application\Command\Sync\{Command, CommandBus, CommandHandler}`
- **Routing**: All commands implementing `App\Shared\Application\Command\Sync\Command` are processed synchronously

#### Enabling Asynchronous Processing

1. **Add async transport in `config/packages/messenger.yaml`**
2. **Create async command interfaces** in `src/Shared/Application/Command/Async/` and implementation `src/Shared/Infrastructure/Messenger/AsyncCommandBus`
3. **Now all commands implementing `App\Shared\Application\Command\Async\Command` are processed asynchronously**

## Improvements can be made

- Pagination Support (BaseLinker API return max 100 orders per request)
- Order Model Simplification (eg. Add Value Objects)
