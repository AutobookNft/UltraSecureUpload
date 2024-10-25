<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\RecordManagerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EGIRecordManager implements RecordManagerInterface
{
    /**
     * Prepara e salva un record nel database specifico per gli EGI.
     *
     * @param array $fileDetails I dettagli del file da salvare.
     * @return mixed Il record creato o una rappresentazione del salvataggio.
     */
    public function SaveRecord(array $fileDetails)
    {
        $encodedLogParams = json_encode([
            'Class' => 'EGIRecordManager',
            'Method' => 'prepareAndSaveRecord',
        ]);
        
        try {
            // Recupera l'utente e il team corrente
            $userId = Auth::id();
            $teamId = Auth::user()->currentTeam->id;

            // Crea l'array specifico per gli EGI
            $record = [
                'user_id' => $this->user_id,
                'owner_id' => $this->user_id,
                'creator' => $this->current_team->creator,
                'owner_wallet' => $this->current_team->creator,
                'upload_id' => $this->generateUploadId(),
                'extension' => $extension,
                'file_hash' => $fileDetails['hash_filename'],
                'file_mime' => $fileDetails['mimeType'],
                'position' => $fileDetails['num'],
                'title' => $fileDetails['default_name'],
                'team_id' => $this->current_team->id,
                'file_crypt' => $fileDetails['crypt_filename'],
                'type' => $fileDetails['fileType'],
                'bind' => 0,
                'price' => $this->floorPrice,
                'floorDropPrice' => $this->floorPrice,
                'show' => true,
            ];

            // Log dell'azione di salvataggio del record
            Log::channel('upload')->info($encodedLogParams, [
                'Action' => 'Record EGI creato',
                'record' => $record
            ]);

            // Simula la creazione del record
            return $record;

        } catch (\Exception $e) {
            Log::channel('upload')->error($encodedLogParams, [
                'Action' => 'Errore durante la preparazione e il salvataggio del record EGI',
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
