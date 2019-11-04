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

#### `CSGO_API_URL`

API server endpoint URL.

#### `CSGO_API_KEY`

API server authentication key.
