<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
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
      Storage::extend('google', function($app, $config) {
        $client = new \Google_Client();
        $client->setClientId($config['clientId']);
        $client->setClientSecret($config['clientSecret']);
        $client->refreshToken($config['refreshToken']);
        $service = new \Google_Service_Drive($client);

        $options = [];
        if(isset($config['teamDriveId'])) {
          $options['teamDriveId'] = $config['teamDriveId'];
        }

        $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
        $driver = new \League\Flysystem\Filesystem($adapter);
        return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
      });
    }
}
