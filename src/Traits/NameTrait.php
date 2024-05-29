<?php

namespace SMSkin\LaravelRabbitMq\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

trait NameTrait
{
    private function generateName(): string
    {
        return strtolower(
            Str::slug(Config::get('app.name')) . ':' . str_replace('\\', '.', static::class)
        );
    }
}
