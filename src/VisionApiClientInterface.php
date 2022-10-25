<?php

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;

/**
 * Базовый интерфейс пакета распознавания Vision Yandex Cloud
 */
interface  VisionApiClientInterface
{
    public function getFreshAIMToken(): IAMTokenResponse;

    function setAIMToken(string $IAMToken);

    public function getDetectedDocument(string $base64ConvertedImage, string $model, string $lang);
}