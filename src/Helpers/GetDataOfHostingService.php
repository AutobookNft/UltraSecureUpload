<?php

namespace Fabio\UltraSecureUpload\Helpers;

class GetDataOfHostingService
{
    /**
     * Get the list of hosting service names in use.
     *
     * @return array The list of hosting service names in use
     */
    public static function nameHostingService(): array
    {
        return collect(config('ultra_secure_upload.hosting_services'))
            ->where('in_use', true)    // Filter hosting services that are in use
            ->pluck('name')            // Extract the 'name' field values
            ->toArray();               // Convert the collection to an array
    }

    /**
     * Get the list of hosting service drivers in use.
     *
     * @return array The list of hosting service drivers in use
     */
    public static function driverHostingService(): array
    {
        return collect(config('ultra_secure_upload.hosting_services'))
            ->where('in_use', true)    // Filter hosting services that are in use
            ->pluck('driver')          // Extract the 'driver' field values
            ->toArray();               // Convert the collection to an array
    }
}