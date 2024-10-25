<?php

namespace Fabio\UltraSecureUpload\Helpers;


class GetFileType 
{
    public static function type($extension): string
    {
        $fileType = '';
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'svg':
            case 'bmp':
            case 'webp':
            case 'ico':
            case 'tif':
            case 'tiff':
            case 'psd':
            case 'raw':                
            case 'eps':
            case 'indd':
            case 'ai':
                $fileType = 'image';
                break;
            case 'mp4':
            case 'avi':
            case 'mov':
            case 'wmv':
            case 'flv':
            case '3gp':
            case 'mkv':
            case 'webm':
            case 'vob':
            case 'ogv':
            case 'ogg':
            case 'qt':
            case 'asf':
            case 'rm':
            case 'swf':
            case 'mpg':
            case 'mpeg':
            case 'm4v':
            case 'm2v':
            case 'mpe':
                $fileType = 'video';
                break;
            case 'mp3':
            case 'wav':
            case 'wma':
            case 'aac':
            case 'flac':
            case 'm4a':
            case 'aiff':
                $fileType = 'audio';
                break;
            case 'pdf':
            case 'txt':
            case 'rtf':
            case 'csv':
            case 'xml':
            case 'json':
            case 'html':
            case 'htm':
            case 'xhtml':
            case 'epub':
            case 'mobi':
                $fileType = 'ebook';
                break;
            default:
                $fileType = 'other';
                break;
        }
        return $fileType;
    }


}
