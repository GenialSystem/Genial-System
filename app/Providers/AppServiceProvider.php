<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Matteo Denni 18/02/2025 inserimento della localizzazione. In questo punto
        //la localizzazione è globale e non ha bisogno di Middlware.
        //Nel caso futuro si può usare questo sistema al posto della traduzione
        //con Google

        if (isset($_COOKIE["googtrans"])) {
            $cookieLanguage = $_COOKIE["googtrans"];
        } else {
            $cookieLanguage = "/it/it";
        }

        App::setLocale(basename($cookieLanguage));
    }
}
