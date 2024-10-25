<?php

namespace Fabio\UltraSecureUpload;

use Fabio\UltraSecureUpload\Services\EGIFileDetailsPreparer;
use Fabio\UltraSecureUpload\Services\EGIRecordManager;
use Illuminate\Support\Facades\Log;

class UploadManager {

    protected $channel = 'upload';

    protected $pathManager;
    protected $recordManager;
    protected $fileDetailsPreparer;

    public function __construct(EGIFileDetailsPreparer $fileDetailsPreparer, EGIRecordManager $recordManager)  
    {
        $this->recordManager = $recordManager;
        $this->fileDetailsPreparer = $fileDetailsPreparer;
    }

    public function upload($file, $user) {
                
        // Prepara i dettagli sul file da salvare sul database
        $fileDetails = $this->fileDetailsPreparer->prepareFileDetails($file, $user);
        
        // Salva il record utilizzando il RecordManager iniettato
        $savedRecord = $this->recordManager->SaveRecord( $fileDetails);

        

    }

    public function validate($file) {
        // Logica di validazione
    }

    public function authorize($user) {
        $encodedLogParams = json_encode([
            'Class' => 'UploadManager',
            'Method' => 'authorize',
        ]);

        if (!$user->can('create own EGI')) {
            Log::channel($this->channel)->info($encodedLogParams, [
                'Action' => 'Unauthorized action attempt by user',
                'user_id' => $user->id,
            ]);
            abort(403, 'Unauthorized action.');
        }
    }
    

    
}