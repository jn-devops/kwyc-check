<?php

namespace Homeful\KwYCCheck\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Homeful\KwYCCheck\Observers\LeadObserver;
use Illuminate\Support\Facades\Event;
use Homeful\KwYCCheck\Models\Lead;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        Lead::observe(LeadObserver::class);
    }
}
