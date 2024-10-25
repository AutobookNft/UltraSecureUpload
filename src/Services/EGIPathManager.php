<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\PathManagerInterface;

class EGIPathManager implements PathManagerInterface
{
    public function getTemporaryFilePath(array $options = []): string
    {
        return '';
    }

    public function getUploadFilePath(array $options = []): string
    {
        
        // Ottengo la route principale per il salvataggio dei file
        $root = config('ultra_secure_upload.root_file_folder', 'uploads');
        
        // Se esiste l'opzione 'user', la utilizziamo
        if (isset($options['user'])) {
            $userId = preg_replace('/[^A-Za-z0-9\-]/', '', $this->user_id);
            if (isset($options['team'])) {
                $teamId = preg_replace('/[^A-Za-z0-9\-]/', '', $this->team_id);
            }
            return $root . '/' . $userId . '/' . $teamId;
        } else{
            return '';
        }

    }
}
