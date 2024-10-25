<?php

namespace Fabio\UltraSecureUpload\Helpers;

 /**
     * Genera un nome casuale.
     *
     * @param int $parameter Parametro per generare il nome predefinito
     * @return string Nome predefinito generato, il primo carattere è un cancelletto
     */

class GenerateDefaultName
{
    public static function defaultName($parameter)
    {
        // Genera la prima parte del nome predefinito, sarà un numero a 4 cifre
        $first = str_pad($parameter, 4, '0', STR_PAD_LEFT);
        
        // Genera la seconda parte del nome predefinito, sarà un numero a 3 cifre
        $second = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        
        // Nome predefinito generato, il primo carattere è un cancelletto
        return "#{$first}{$second}";
    }
}

