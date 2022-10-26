<?php

declare(strict_types=1);

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\exceptions\IAMFileException;
use mfteam\ocrVisionYandexCloud\helpers\FileHelper;
use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;

/**
 * Класс работы с файлами: получение-запись IAM токена, работа с изображением и pdf
 */
class FileAssist implements FileAssistInterface
{
    protected $IAMFileName;

    /**
     * @param string|null $AIMFileName
     * @throws exceptions\IAMFileException
     */
    public function __construct(?string $AIMFileName)
    {
        $this->IAMFileName = $AIMFileName ?? FileHelper::getTempFileName();
    }

    /**
     * @return IAMTokenResponse|null
     * @throws IAMFileException
     */
    public function readAIMToken(): ?IAMTokenResponse
    {
        try {
            if (!file_exists($this->IAMFileName)) {
                throw new IAMFileException('IAM token file does not exists');
            }
            $IAMToken = explode(PHP_EOL, file_get_contents($this->IAMFileName));

            if ($IAMToken !== false) {
                if (count($IAMToken) === 2 && (int) $IAMToken[0] < time()) {
                    return new IAMTokenResponse($IAMToken[0], $IAMToken[1]);
                }
            }

            return null;
        } catch (\Exception $e) {
            throw new IAMFileException($e->getMessage());
        }
    }

    /**
     * @param IAMTokenResponse $AIMToken
     * @return void
     */
    public function writeAIMToken(IAMTokenResponse $AIMToken): void
    {
        file_put_contents(
            $this->IAMFileName,
            $AIMToken->getIAMToken() . PHP_EOL . $AIMToken->getExpiredAtAsUnixTime()
        );
    }

    /**
     * @param string $fileName
     * @return string
     * @throws exceptions\ImageFileException
     */
    public function convertDetectedDocument(string $fileName): string
    {
        FileHelper::checkProcessedFile($fileName);

        return FileHelper::convertProcessedFile($fileName);
    }
}