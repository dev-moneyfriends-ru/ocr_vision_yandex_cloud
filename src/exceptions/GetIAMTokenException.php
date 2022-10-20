<?php

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Ошибка получения IAM токена
 */
class GetIAMTokenException extends \Exception
{
    public function getName()
    {
        return 'GetIAMToken';
    }
}