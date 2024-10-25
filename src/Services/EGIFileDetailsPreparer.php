<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\EncryptionServiceInterface;
use Fabio\UltraSecureUpload\Contracts\FileDetailsPreparerInterface;
use Fabio\UltraSecureUpload\Contracts\GetFileTypeInterface;
use Fabio\UltraSecureUpload\Contracts\PathManagerInterface;
use Fabio\UltraSecureUpload\Helpers\GenerateDefaultName;
use Fabio\UltraSecureUpload\Helpers\GenerateNextPosition;
use Fabio\UltraSecureUpload\Helpers\GetFileType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EGIFileDetailsPreparer implements FileDetailsPreparerInterface
{

    protected $pathManager;
    protected $encryptionService;
    protected $getFileType;



    public function __construct(PathManagerInterface $pathManager, EncryptionServiceInterface $encryptionService)
    {
        $this->pathManager = $pathManager;
        $this->encryptionService = $encryptionService;
            }


    public function prepareFileDetails($file, $user): array
    {
        $encodedLogParams = json_encode([
            'Class' => 'EGIFileDetailsPreparer',
            'Method' => 'prepareFileDetails',
        ]);

        $channel = 'upload';

        try {
            
            // Logiche specifiche per EGI
            $userId = $user->id;
            $teamId = $user->currentTeam->id;
            
            $destinationPathImage = $this->pathManager->getUploadFilePath(['user' => $userId, 'team' => $teamId]);
            $directory = storage_path($destinationPathImage);
            $extension = $file->extension();
            $fileType = GetFileType::type($extension);
            $hashFilename = $file->hashName();
            $mimeType = $file->getMimeType();
            $tempRealPath = $file->getRealPath();
            $originalFilename = $file->getClientOriginalName();

            $crypt_filename = $this->encryptionService->encrypt($originalFilename);
            $nextPosition = GenerateNextPosition::generateNextPosition('teams_items', 'team_id', $teamId, 'position');
            $default_name = GenerateDefaultName::defaultName($nextPosition);

            Log::channel($channel)->info($encodedLogParams, [
                'Action' => 'Dettagli del file preparati',
                'hash_filename' => $hashFilename,
                'mime_type' => $mimeType,
                'temp_real_path' => $tempRealPath,
                'original_filename' => $originalFilename,
                'directory' => $directory,
                'default_name' => $default_name,
                'crypt_filename' => $crypt_filename,
                'user_id' => $userId,
                'team_id' => $teamId,
                
            ]);

            return compact('hashFilename', 'extension', 'mimeType', 'tempRealPath', 'originalFilename', 'directory', 'default_name', 'crypt_filename', 'num', 'userId', 'teamId');

        } catch (\Exception $e) {
            Log::channel($channel)->error($encodedLogParams, [
                'Action' => 'Errore durante la preparazione dei dettagli del file',
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

}
