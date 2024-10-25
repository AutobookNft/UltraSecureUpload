<?php

namespace Fabio\UltraSecureUpload\Services;

use Fabio\UltraSecureUpload\Contracts\PathManagerInterface;

class DefaultPathManager implements PathManagerInterface
{
    public function getTemporaryFilePath($options = []): string
    {
        $path = $options['path'] ?? 'temp';
        return storage_path('app/temp/' . $path);
    }

    public function getUploadFilePath($options = []): string
    {
        $path = $options['path'] ?? 'uploads';
        return storage_path('app/uploads/' . $path);
    }
}
