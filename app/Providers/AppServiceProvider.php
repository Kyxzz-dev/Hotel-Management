<?php



namespace App\Providers;



use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;



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

        // Hindari error saat dijalankan lewat CLI (seperti php artisan migrate)

        if (app()->runningInConsole()) {

            return;

        }



        $host = request()->getHost();

        $scheme = request()->getScheme();



        // Deteksi jika menggunakan ngrok (otomatis paksa HTTPS)

        $isNgrok = str_contains($host, 'ngrok.io') || 

                   str_contains($host, 'ngrok.app') || 

                   str_contains($host, 'ngrok.dev') ||

                   str_contains($host, 'ngrok-free.app');

        

        if ($isNgrok || $scheme === 'https') {

            // Force HTTPS for all URLs

            URL::forceScheme('https');

            

            // Set the correct app URL

            $appUrl = 'https://' . $host;

            config(['app.url' => $appUrl]);

            

            // Force root URL for assets

            URL::forceRootUrl($appUrl);

            

            // Ensure asset URLs use HTTPS

            URL::forceScheme('https');

        }

    }

}