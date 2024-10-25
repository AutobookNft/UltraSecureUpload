<?php

namespace Fabio\UltraSecureUpload\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateNextPosition
{
    public static function generateNextPosition($table, $filterColumn, $filterValue, $incrementColumn)
    {
        try {
            // Trova il valore massimo nella colonna specificata, filtrato dal filtro fornito
            $maxValue = DB::table($table)
                ->where($filterColumn, $filterValue)
                ->max($incrementColumn);

            // Se non ci sono record, $maxValue sarà null. Coalesce a 0 in questo caso.
            return ($maxValue ?? 0) + 1;

        } catch (\Exception $e) {
            // Log in caso di errore
            Log::channel('upload')->error("Errore durante la generazione del numero incrementale: {$e->getMessage()}");
            return 0;
        }
    }
}

