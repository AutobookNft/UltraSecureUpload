<?php

namespace Fabio\UltraSecureUpload\Contracts;

interface EncryptionServiceInterface
{
    /**
     * Crittografa una stringa fornita.
     *
     * @param string $string Stringa da crittografare.
     * @return string|bool La stringa crittografata o false in caso di errore.
     */
    public function encrypt(string $string): string|bool;

    /**
     * Decrittografa una stringa fornita.
     *
     * @param string $string Stringa crittografata da decrittografare.
     * @return string|bool La stringa decrittografata o false in caso di errore.
     */
    public function decrypt(string $string): string|bool;
}
