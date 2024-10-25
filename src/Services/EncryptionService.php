<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\EncryptionServiceInterface;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Defuse\Crypto\Exception\BadFormatException;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Illuminate\Support\Facades\Log;

class EncryptionService implements EncryptionServiceInterface
{
    protected string $keyAscii;

    public function __construct()
    {
        // Recupera la chiave dal file di configurazione (e.g., da .env)
        $this->keyAscii = config('app.data_crypto_key');
    }

    public function encrypt(string $string): string|bool
    {
        if (empty($string)) {
            return false;
        }

        try {
            // Carica la chiave di crittografia
            $key = Key::loadFromAsciiSafeString($this->keyAscii);
        } catch (BadFormatException $e) {
            $this->logError('EncryptionService', 'encrypt', $e->getMessage(), $e->getCode());
            return false;
        }

        try {
            // Crittografa la stringa
            return Crypto::encrypt($string, $key);
        } catch (EnvironmentIsBrokenException $e) {
            $this->logError('EncryptionService', 'encrypt', $e->getMessage(), $e->getCode());
            return false;
        }
    }

    public function decrypt(string $string): string|bool
    {
        if (empty($string)) {
            return false;
        }

        try {
            // Carica la chiave di crittografia
            $key = Key::loadFromAsciiSafeString($this->keyAscii);
        } catch (BadFormatException $e) {
            $this->logError('EncryptionService', 'decrypt', $e->getMessage(), $e->getCode());
            return false;
        }

        try {
            // Decrittografa la stringa
            return Crypto::decrypt($string, $key);
        } catch (WrongKeyOrModifiedCiphertextException|EnvironmentIsBrokenException $e) {
            $this->logError('EncryptionService', 'decrypt', $e->getMessage(), $e->getCode());
            return false;
        }
    }

    /**
     * Registra un errore nel log.
     *
     * @param string $class Classe in cui è stato rilevato l'errore.
     * @param string $method Metodo in cui è stato rilevato l'errore.
     * @param string $message Messaggio di errore.
     * @param int $code Codice di errore.
     */
    protected function logError(string $class, string $method, string $message, int $code): void
    {
        $errorDetails = [
            'Error' => 'Encryption Error',
            'Class' => $class,
            'Method' => $method,
            'Situation' => $message,
            'SystemError' => $code,
        ];
        Log::channel('upload')->error(json_encode($errorDetails));
    }
}
