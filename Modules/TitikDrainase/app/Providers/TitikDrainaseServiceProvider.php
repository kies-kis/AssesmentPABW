<?php

namespace Modules\TitikDrainase\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class TitikDrainaseServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'TitikDrainase';

    protected string $nameLower = 'titikdrainase';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
