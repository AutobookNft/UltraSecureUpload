<?php

namespace Fabio\UltraSecureUpload\Contracts;

interface SaveFilesInterface
{
    /**
     * Salva il file sul disco.
     *
     * @param string $fileToSave Il file da salvare
     * @param string $tempRealPath Il percorso reale del file temporaneo
     * @param string $destinationPathImage Il percorso in cui salvare l'immagine
     * @return string Il percorso completo del file salvato
     */
    public function save(string $fileToSave, string $tempRealPath, string $destinationPathImage): string;
}