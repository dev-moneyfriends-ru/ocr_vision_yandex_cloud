<?php

declare(strict_types=1);

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;
use mfteam\ocrVisionYandexCloud\templates\AbstractTemplate;

/**
 * Базовый интерфейс пакета распознавания Vision Yandex Cloud
 */
interface  VisionApiClientInterface
{
    public function getFreshAIMToken(): IAMTokenResponse;

    function setAIMToken(string $IAMToken);

    public function getDetectedDocument(string $pathToFile, string $base64ConvertedImage, AbstractTemplate $template, string $lang);
}