<?php

declare(strict_types=1);

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Ошибка работы с файлом для распознания
 */
class ImageFileException extends \Exception
{
    public function getName()
    {
        return 'ImageFile';
    }
}