<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Document;
use App\Models\SoMasterList;
use App\Models\Outgoing;
use App\Policies\DocumentPolicy;
use App\Policies\SoMasterListPolicy;
use App\Policies\OutgoingPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    
        Document::class => DocumentPolicy::class,
        SoMasterList::class => SoMasterListPolicy::class,
        Outgoing::class => OutgoingPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
