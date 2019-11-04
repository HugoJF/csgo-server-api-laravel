# CS:GO Server API - Laravel package
Laravel package to interface [CS:GO Server API](https://github.com/HugoJF/csgo-server-api).

[![Build Status](https://travis-ci.org/HugoJF/csgo-server-api-laravel.svg?branch=v2.0)](https://travis-ci.org/HugoJF/csgo-server-api-laravel)
[![Coverage Status](https://coveralls.io/repos/github/HugoJF/csgo-server-api-laravel/badge.svg?branch=v2.0)](https://coveralls.io/github/HugoJF/csgo-server-api-laravel?branch=v2.0)

## How it works
This package interfaces [my CS:GO API](https://github.com/HugoJF/csgo-server-api) main endpoints:

`/send` which you can send a command, with a delay to a single server;

`/sendAll` which you can send a command, with a delay to all servers controlled by the API;

## Requirements
* PHP 7.*
    
* [CS:GO Server API](https://github.com/HugoJF/csgo-server-api) installation
  
## Installation
Using Composer, run:

`composer require hugojf/csgo-server-api-laravel`
  
Publish config:

`php artisan vendor:publish --provider="hugojf\CsgoServerApi\Providers\PackageServiceProvider"`

## Configuration
Any configuration can be modified inside `configs/csgo-api.php`

#### `CSGO_API_URL=http://my-csgo-server-api.com/`

API server endpoint URL.

#### `CSGO_API_KEY=abcdef123456`

API server authentication key.

## Usage

#### Creating a command
```php
$myCommand = new Command($command, $delay = 0, $wait = false);
```

###### Parameters
* `$command` is the command to be executed;
* `$delay` tells how long the API should wait before sending the command;
* `$wait` tells the API to wait for server response before responding the request.

###### Examples
```php
// Get server stats
$statsCommand = new Command('stats', 0,  true);

// Kick bots
$botsCommand = new Command('bot_kick');

// Schedule say message
$sayCommand = new Command('say Hi!', 30000)
```

#### Creating a server
```php
$myServer = new Server($address, $port);
```

###### Parameters
* `$address` is the full address or just IP;
* `$port` is the server port.

###### Examples
```php
// Using IP and Port
$server1 = new Server('177.54.150.15', 27001);

// Using full address
$server2 = new Server('177.54.150.15:27002');
```


#### Sending a list of commands to a list of servers

```php
// Sends `stats` and `status` to both servers
//
// You can replace `direct` with `to`, they are the same
CsgoApi::direct(ByCommandSummary::class)->addCommand([
    new Command('stats', 1500, true),
    new Command('status', 1500, true),
])->addServer(
    new Server('177.54.150.15:27001'),
    new Server('177.54.150.15:27002'),
)->send();

// Expected response:
//  [
//      "stats"  => [
//          "177.54.150.15:27001" => "response-1",
//          "177.54.150.15:27001" => "response-2",
//      ],
//      "status" => [
//          "177.54.150.15:27001" => "response-3",
//          "177.54.150.15:27001" => "response-4",
//      ],
//   ]
```

#### Broadcasting a list of commands to all servers controlled by the API
```php
// Sends `say` and `quit` to all servers
//
// You can replace `broadcast` with ``, they are the same
CsgoApi::broadcast(ByCommandSummary::class)->addCommand([
    new Command('say "Closing server for maintenance in 30 seconds', 0),
    new Command('say "Closing server for maintenance in 15 seconds', 15000),
    new Command('say "Closing server for maintenance in 5 seconds', 25000),
    new Command('quit', 30000),
])->send();
```

#### Different ways you can add commands or servers
In an attempt to avoid instantiating every server or command, you can use the following formats:

###### Servers
```php
// Creating a DirectSender
$sender = CsgoApi::direct();

// Using full Server object
//
// All 4 methods are identical, use what feels right
$sender->addServer(new Server('177.54.150.15:27001'));
$sender->addServers(new Server('177.54.150.15:27001'));
$sender->server(new Server('177.54.150.15:27001'));
$sender->servers(new Server('177.54.150.15:27001'));

// Using list of Server objects
$sender->addServer([
    new Server('177.54.150.15:27001'),
    new Server('177.54.150.15:27002'),
]);

// Using string address
$sender->addServer('177.54.150.15:27002');

// Using list of string addresses
$sender->addServer([
    '177.54.150.15:27001',
    '177.54.150.15:27002',
]);

// Using IP and Port separately
$sender->addServer('177.54.150.15', 27002);

// Using list of IP and Ports
$sender->addServer([
    ['177.54.150.15', 27001],
    ['177.54.150.15', 27002],
]);
```

###### Commands
```php
// Creating a DirectSender
$sender = CsgoApi::direct();

// Using full Command object
//
// All 4 methods are identical, use what feels right
$sender->addCommand(new Command('stats', 1000, false));
$sender->addCommands(new Command('stats', 1000, false));
$sender->command(new Command('stats', 1000, false));
$sender->commands(new Command('stats', 1000, false));

// Using list of Command objects
$sender->addCommandItem([
    new Command('stats', 1500, false),
    new Command('status', 1500, false),
]);

// Using command parameters directly
$sender->addCommand('stats', 1500, false);

// Using list of command parameters
$sender->addCommand([
    ['stats', '1500', false],
    ['status', '1500', false],
]);
```

#### Changing summary class
You can change the way responses are grouped by passing a new Summary class

###### `ByCommandSummary::class`
Groups responses by command first and then server after.

###### `ByServerSummary::class`
Groups responses by server first and then command after.

###### Example
```php
$directByCommandSender = CsGoApi::direct(ByCommandSummary::class);
$directByServerSender = CsGoApi::direct(ByServerCummary::class);

$broadcastByCommandSender = CsGoApi::broadcast(ByCommandSummary::class);
$broadcastByServerSender = CsGoApi::broadcast(ByServerCummary::class);
```
