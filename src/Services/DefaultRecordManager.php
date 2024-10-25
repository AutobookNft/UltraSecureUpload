<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\RecordManagerInterface;
use Illuminate\Support\Facades\Log;

class DefaultRecordManager implements RecordManagerInterface
{

    
    /**
     * Prepara e salva un record nel database.
     *
     * @param array $fileDetails I dettagli del file da salvare.
     * @return mixed Il record creato o una rappresentazione del salvataggio.
     */
    public function SaveRecord(array $fileDetails)
    {
        $encodedLogParams = json_encode([
            'Class' => 'DefaultRecordManager',
            'Method' => 'prepareAndSaveRecord',
        ]);
        
        
        try {
            // Esempio generico per salvare i dettagli del file
            // In un contesto reale, questo dovrebbe essere adattato per interagire con il tuo database
            $record = [
                'file_name' => $fileDetails['fileName'],
                'mime_type' => $fileDetails['mimeType'],
                'file_path' => $fileDetails['directory'],
                'created_at' => now(),
            ];

            // Log dell'azione di salvataggio del record
            Log::channel('upload')->info($encodedLogParams, [
                'Action' => 'Record creato',
                'record' => $record
            ]);

            // Simula la creazione del record
            return $record;

        } catch (\Exception $e) {
            Log::channel('upload')->error($encodedLogParams, [
                'Action' => 'Errore durante la preparazione e il salvataggio del record',
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
