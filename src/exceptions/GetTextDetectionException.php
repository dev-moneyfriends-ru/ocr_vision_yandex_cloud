<?php

declare(strict_types=1);

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Ошибка получения данных по распознаванию документа
 */
class GetTextDetectionException extends \Exception
{
    public function getName()
    {
        return 'GetTextDetection';
    }
}