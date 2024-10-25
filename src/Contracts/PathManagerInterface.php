<?php

namespace Fabio\UltraSecureUpload\Contracts;

interface PathManagerInterface
{
    public function getTemporaryFilePath(array $options = []): string;

    public function getUploadFilePath(array $options = []): string;
}
