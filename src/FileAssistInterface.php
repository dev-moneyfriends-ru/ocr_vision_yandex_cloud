<?php

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;

/**
 * Интерфейс работы с файлами: получение-запись IAM токена, работа с изображением и pdf
 */
interface  FileAssistInterface
{
    public function __construct(?string $AIMFileName);

    /**
     * Чтение времени действия/токена из указанного временного файла
     *
     * @return mixed
     */
    public function readAIMToken(): ?string;

    /**
     * Запись полученного AIM токена
     *
     * @param IAMTokenResponse $AIMToken
     * @return bool
     */
    public function writeAIMToken(IAMTokenResponse $AIMToken): void;

    /**
     * По указанному пути находится и декодируется файл в Base64
     *
     * @param string $fileName
     * @return string
     */
    public function convertDetectedDocument(string $fileName): string;
}