<?php

namespace Fabio\UltraSecureUpload\Contracts;

interface FileDetailsPreparerInterface
{
    /**
     * Prepara i dettagli del file per l'elaborazione.
     *
     * @param \Illuminate\Http\UploadedFile $file File caricato
     * @param mixed $user Utente che ha caricato il file
     * @return array Dettagli del file
     */
    public function prepareFileDetails($file, $user): array;
}
