<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Document;
use App\Models\SoMasterList;
use App\Models\Outgoing;
use App\Models\User;
use App\Models\HEI;
use App\Policies\DocumentPolicy;
use App\Policies\SoMasterListPolicy;
use App\Policies\OutgoingPolicy;
use App\Policies\UserPolicy;
use App\Models\Cav;
use App\Policies\CavPolicy;
use App\Models\Record;
use App\Policies\RecordPolicy;
use App\Policies\HEIPolicy;
use App\Policies\GmailPolicy;
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
        User::class => UserPolicy::class,
        Cav::class => CavPolicy::class,
        Record::class => RecordPolicy::class,
        HEI::class => HEIPolicy::class,
        'App\Http\Controllers\GmailController' => 'App\Policies\GmailPolicy',

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
