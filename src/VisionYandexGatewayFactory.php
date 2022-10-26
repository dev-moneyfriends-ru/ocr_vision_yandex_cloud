<?php

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\exceptions\IAMFileException;
use mfteam\ocrVisionYandexCloud\templates\AbstractTemplate;

/**
 * Фабрика Api клиента
 *
 * Class VisionYandexGateway
 * @package mfteam\VisionYandexGatewayFactory
 */
class VisionYandexGatewayFactory
{
    /**
     * @param string $oAuthToken
     * @param string $folderId
     * @param GuzzleHttp\Client $guzzleClient
     * @param AbstractTemplate $template
     * @param string|null $IAMFile
     * @return VisionYandexGateway
     * @throws IAMFileException
     */
    public static function instanceClient(
        string $oAuthToken,
        string $folderId,
        $guzzleClient,
        AbstractTemplate $template,
        string $IAMFile = null
    ): VisionYandexGateway
    {
        $clientApi = new VisionApiClient($oAuthToken, $folderId, $guzzleClient);
        $fileAssist = new FileAssist($IAMFile);

        return new VisionYandexGateway($clientApi, $fileAssist, $template);
    }
}
