<?php

namespace mfteam\ocrVisionYandexCloud\helpers;

use mfteam\ocrVisionYandexCloud\exceptions\IAMFileException;
use mfteam\ocrVisionYandexCloud\exceptions\ImageFileException;

class FileHelper
{
    const ALLOWED_FILE_EXTENSIONS = [
        'jpg',
        'png',
        'pdf',
    ];

    /**
     * @return string
     * @throws IAMFileException
     */
    public static function getTempFileName(): string
    {
        $fileName = tempnam(sys_get_temp_dir(), 'VisionAnalyzeApi_');

        if ($fileName === false) {
            throw new IAMFileException("tempnam() couldn't create a temp file.");
        }

        return $fileName;
    }

    public static function getExtension(string $filename): ?string
    {
        return pathinfo(strtolower($filename))['extension'] ?? null;
    }

    /**
     * @param string $filename
     * @return void
     * @throws ImageFileException
     */
    public static function checkProcessedFile(string $filename)
    {
        // Проверка и подготовка распознаваемого документа
        if (!file_exists($filename)) {
            throw new ImageFileException('Image file does not exists');
        }
        // todo: убрать проверку размера после ресайза изображения
        if (filesize($filename) > 1048576) {
            throw new ImageFileException('Image file must be less 1 Mb');
        }
        if (!in_array(self::getExtension($filename), self::ALLOWED_FILE_EXTENSIONS)) {
            throw new ImageFileException(
                'Allowed only ' . implode(', ', self::ALLOWED_FILE_EXTENSIONS) . ' extensions'
            );
        }
    }

    public static function convertProcessedFile(string $filename): string
    {
        // todo: сделать изменение изображения/pdf-файла и допустимые ширину-высоту

        try {
            return base64_encode(file_get_contents($filename));
        } catch (\Exception $e) {
            throw new ImageFileException($e->getMessage());
        }
    }
}
