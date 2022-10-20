<?php

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Некорректные данные для доступа
 */
class AccessDeniedException extends \Exception
{
    public const ERROR_IAM_TOKEN = 16;

    public function getName()
    {
        return 'AccessDenied';
    }
}
