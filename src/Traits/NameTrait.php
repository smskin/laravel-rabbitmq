<?php

namespace SMSkin\LaravelRabbitMq\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

trait NameTrait
{
    public function getName(): string
    {
        return strtolower(
            Str::slug(Config::get('app.name')) . ':' . str_replace('\\', '.', static::class)
        );
    }
}
