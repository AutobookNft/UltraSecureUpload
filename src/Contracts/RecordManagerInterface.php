<?php

namespace Fabio\UltraSecureUpload\Contracts;

interface RecordManagerInterface
{
    /**
     * Prepara e salva un record nel database.
     *
     * @param array $fileDetails
     * @return mixed Il record creato o una rappresentazione del salvataggio.
     */
    public function SaveRecord(array $fileDetails);
}
