<?php

namespace App\Providers;

use App\Service\PaymentService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequet;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerInertia();

        $this->app->bind(PaymentService::class, function($app) {
            return new PaymentService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    public function registerInertia()
    {
        // Inertia::version(function () {
        //     return md5_file(public_path('mix-manifest.json'));
        // });

        Inertia::share([
            'canLogin' => function () {
                return Auth::check();
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
