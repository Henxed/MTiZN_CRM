<?php

namespace App\Providers;

use App\Models\Notification;
use App\Service\PaymentService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequet;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerInertia();

        // $this->app->bind(PaymentService::class, function($app) {
        //     return new PaymentService();
        // });
    }

    public function boot()
    {
        //
    }


    public function registerInertia()
    {

        Inertia::share([
            // 'recaptcha_site_key' => function () {
            //     return env('GOOGLE_RECAPTCHA_SITE_KEY');
            // },
            'canLogin' => function () {
                return Auth::check();
            },
            'n_count' => function () {
                return Auth::check() ? Notification::where('user_id', Auth::user()->id)->whereNull('read_at')->count() : 0;
            },
            'access' => function () {
                return [
                    'can' => Auth::check() ? Auth::user()->All_Permissions() : null,
                    'role' => Auth::check() ? Auth::user()->getRoleNames() : null
                ];
            },
            'flash' => function (HttpRequet $request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'info' => $request->session()->get('info'),
                ];
            },
        ]);

    }
}
