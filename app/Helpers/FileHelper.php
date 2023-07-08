<?php

namespace App\Helpers;

class FileHelper
{

    /**
     * Aplicar formato al nombre de un fichero antes de aplicar "move upload"
     *
     * @param string $fileName
     * @return string|string[]|null
     */
    public static function sanitizerFileName(string $fileName){
        $fileName = preg_replace('/.[^.]*$/', '', $fileName);
        $fileName = preg_replace("/[^a-zA-Z0-9]+/", "", $fileName);

        return $fileName;
    }

    /**
     * Obtener url base64 de un fichero.
     *
     * @param string $pathFile
     * @param string $mime
     * @return string
     */
    public static function getDataURI(string $pathFile, string $mime = '') {
        return 'data:'.(function_exists('mime_content_type') ? mime_content_type($pathFile) : $mime).';base64,'.base64_encode(file_get_contents($pathFile));
    }
}