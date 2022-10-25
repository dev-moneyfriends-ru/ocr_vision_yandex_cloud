<?php

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\exceptions\ModelEnumException;

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
     * @param string $model
     * @param $guzzleClient
     * @param string|null $AIMFile
     * @param string|null $lang
     * @return VisionYandexGateway
     * @throws ModelEnumException
     */
    public static function instanceClient(
        string $oAuthToken,
        string $folderId,
        string $model,
        $guzzleClient,
        string $AIMFile = null,
        string $lang = null
    ): VisionYandexGateway
    {
        $clientApi = new VisionApiClient($oAuthToken, $folderId, $guzzleClient);
        $fileAssist = new FileAssist($AIMFile);

        return new VisionYandexGateway($clientApi, $fileAssist, $model, $lang);
    }
}
