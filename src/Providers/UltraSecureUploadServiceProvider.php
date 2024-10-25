<?php

namespace Fabio\UltraSecureUpload\Providers;

use Fabio\UltraSecureUpload\Contracts\EncryptionServiceInterface;
use Fabio\UltraSecureUpload\Contracts\FileDetailsPreparerInterface;
use Fabio\UltraSecureUpload\Contracts\GetFileTypeInterface;
use Fabio\UltraSecureUpload\Contracts\PathManagerInterface;
use Fabio\UltraSecureUpload\Contracts\RecordManagerInterface;
use Fabio\UltraSecureUpload\Services\DefaultFileDetailsPreparer;
use Fabio\UltraSecureUpload\Services\DefaultPathManager;
use Fabio\UltraSecureUpload\Services\DefaultRecordManager;
use Fabio\UltraSecureUpload\Services\EGIRecordManager;
use Fabio\UltraSecureUpload\Services\EncryptionService;
use Fabio\UltraSecureUpload\Services\GetFileType;
use Illuminate\Support\ServiceProvider;
use Fabio\UltraSecureUpload\UploadManager;

class UltraSecureUploadServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->singleton(UploadManager::class);
        
        // Registrazione del servizio UploadManager nel Service Container
        $this->app->singleton(FileDetailsPreparerInterface::class, DefaultFileDetailsPreparer::class);
      
        // Registrazione del servizio PathManagerInterface
        $this->app->singleton(PathManagerInterface::class, DefaultPathManager::class);

        // Registrazione del servizio PathManagerInterface
        $this->app->singleton(RecordManagerInterface::class, EGIRecordManager::class);

        // Binding di default per RecordManager
        $this->app->singleton(RecordManagerInterface::class, DefaultRecordManager::class);

        $this->app->singleton(EncryptionServiceInterface::class, EncryptionService::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if (file_exists($routes = __DIR__.'/../routes/web.php')) {
            $this->loadRoutesFrom($routes);
        }
        
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ultra_secure_upload');

        // Pubblicazione dei file di configurazione, rotte, etc.
        $this->publishes([
            __DIR__.'/../config/ultra_secure_upload.php' => config_path('ultra_secure_upload.php'),
        ], 'config');
        
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ultra_secure_upload'),
        ], 'views');
        
        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/ultra_secure_upload.php'),
        ], 'routes');

        // Se necessario, puoi includere anche rotte o viste.
    }
}
