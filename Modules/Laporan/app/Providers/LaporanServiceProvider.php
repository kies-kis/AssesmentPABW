<?php

namespace Modules\Laporan\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class LaporanServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Laporan';

    protected string $nameLower = 'laporan';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
