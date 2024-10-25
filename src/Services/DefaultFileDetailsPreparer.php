<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\PerfectConfigManager\ConfigManager;
use Fabio\UltraSecureUpload\Contracts\EncryptionServiceInterface;
use Fabio\UltraSecureUpload\Contracts\FileDetailsPreparerInterface;
use Fabio\UltraSecureUpload\Contracts\PathManagerInterface;
use Fabio\UltraSecureUpload\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;

class DefaultFileDetailsPreparer implements FileDetailsPreparerInterface
{

    protected $pathManager;
    protected $encryptionService;

    protected $routeChannel;
    protected $encodedLogParams;

    public function __construct(PathManagerInterface $pathManager, EncryptionServiceInterface $encryptionService)
    {
        $this->pathManager = $pathManager;
        $this->encryptionService = $encryptionService;
    }


    public function prepareFileDetails($file, $user): array
    {
        $encodedLogParams = json_encode([
            'Class' => 'DefaultFileDetailsPreparer',
            'Method' => 'prepareFileDetails',
        ]);

        $channel = 'upload';

        try {

            $userId = $user->id;
            
            $destinationPathImage = $this->pathManager->getUploadFilePath(['user' => $userId]);
            $directory = storage_path($destinationPathImage);
            $extension = $file->extension();
            $hashFilename = $file->hashName();
            $mimeType = $file->getMimeType();
            $tempRealPath = $file->getRealPath();
            $originalFilename = $file->getClientOriginalName();
            $crypt_filename = $this->encryptionService->encrypt($originalFilename);
            $user_id = $user->id;
                       
            if ($crypt_filename === false) {
                Log::channel($channel)->error($encodedLogParams. ' Errore durante la crittografia del nome del file');
                throw new CustomException('ERROR_DURING_FILE_NAME_ENCRYPTION');
            }

            Log::channel($channel)->info($encodedLogParams, [
                'Action' => 'Dettagli del file preparati',
                'hash_filename' => $hashFilename,
                'mime_type' => $mimeType,
                'temp_real_path' => $tempRealPath,
                'original_filename' => $originalFilename,
                'crypt_filename' => $crypt_filename,
                'user_id' => $user->id,
                
            ]);

            // Genera il percorso di destinazione predefinito (può essere personalizzato tramite un PathManager)
            $directory = storage_path('app/uploads/');
            Log::channel($channel)->info($encodedLogParams, [
                'Action' => 'Percorso della cartella di destinazione',
                'directory' => $directory
            ]);

            return compact('hashFilename', 'extension', 'mimeType', 'tempRealPath', 'originalFilename', 'directory', 'crypt_filename', 'user_id');

        } catch (\Exception $e) {
            Log::channel($channel)->error($encodedLogParams, [
                'Action' => 'Errore durante la preparazione dei dettagli del file',
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Determina il tipo di file in base all'estensione.
     *
     * @param string $extension Estensione del file
     * @return string Tipo di file
     */
    protected function getFileType($extension)
    {
        $allowedTypes = config('AllowedFileType.collection.allowed');
        return $allowedTypes[$extension] ?? 'unknown';
    }

    /**
     * Initialize the exception handling process.
     *
     * @param string $stringCode
     * @return void
     */
    private function start(): void
    {
        // Attempt to get log channel from config, fall back to 'error_manager'
        try {
            $this->routeChannel = ConfigManager::getConfig('log_channel') ?? 'error_manager';
        } catch (\Exception $e) {
            // Fallback to a default channel if ConfigManager fails
            $this->routeChannel = 'error_manager';
        }

        // Prepare the log message
        $this->encodedLogParams = json_encode([
            'Class' => 'DefaultFileDetailsPreparer',
        ]);

    }

}
