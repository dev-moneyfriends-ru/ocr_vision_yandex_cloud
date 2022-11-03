<?php

declare(strict_types=1);

namespace  mfteam\ocrVisionYandexCloud;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use mfteam\ocrVisionYandexCloud\exceptions\AccessDeniedException;
use mfteam\ocrVisionYandexCloud\exceptions\GetIAMTokenException;
use mfteam\ocrVisionYandexCloud\exceptions\GetTextDetectionException;
use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;
use mfteam\ocrVisionYandexCloud\templates\AbstractTemplate;

/**
 * Класс взаимодействия с API Yandex
 *
 * Class VisionApiClient
 * @package mfteam\ocrVisionYandexCloud
 */
class VisionApiClient implements VisionApiClientInterface
{
    protected const TEXT_DETECTION_HOST = 'https://vision.api.cloud.yandex.net/vision/v1/batchAnalyze';
    protected const TEXT_DETECTION_METHOD = 'POST';

    /**
     * @var string
     */
    protected $IAMHost = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';
    protected $IAMMethod = 'POST';

    /**
     * @see https://cloud.yandex.ru/docs/resource-manager/operations/folder/get-id
     *
     * @var string любой folder ID от YandexCloud
     */
    protected $folderId;

    /**
     * @see https://cloud.yandex.ru/docs/iam/operations/iam-token/create
     *
     * @var string Ваш oAuth token от Yandex
     */
    protected $oAuthToken;

    /**
     * @see https://cloud.yandex.ru/docs/iam/operations/iam-token/create
     *
     * @var string Выданный IAM токен
     */
    protected $IAMToken = null;

    /**
     * @var Client $guzzleClient
     */
    protected $guzzleClient;

    /**
     * @param string $oAuthToken
     * @param string $folderId
     * @param Client $guzzleClient
     */
    public function __construct(string $oAuthToken, string $folderId, $guzzleClient)
    {
        $this->setOAuthToken($oAuthToken);
        $this->setFolderId($folderId);
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @return IAMTokenResponse
     * @throws GetIAMTokenException|\GuzzleHttp\Exception\GuzzleException
     */
    public function getFreshAIMToken(): IAMTokenResponse
    {
        // Получить свежий IAM токен
        try {
            $contents = $this->guzzleClient->request(
                $this->IAMMethod,
                $this->IAMHost,
                ['json' => ['yandexPassportOauthToken' => $this->oAuthToken]]
            )
                ->getBody()
                ->getContents();
            $contents = json_decode($contents, true);

            if (!isset($contents['iamToken']) || !isset($contents['expiresAt'])) {
                throw new GetIAMTokenException('Wrong response while getting IAM Token.');
            }

            return new IAMTokenResponse($contents['iamToken'], $contents['expiresAt']);
        } catch (\Exception $e) {
            throw new GetIAMTokenException($e->getMessage());
        }
    }

    public function setAIMToken(string $IAMToken)
    {
        $this->IAMToken = $IAMToken;
    }

    public function setOAuthToken(string $oAuthToken)
    {
        $this->oAuthToken = $oAuthToken;
    }

    /**
     * @return string
     */
    public function getOAuthToken(): string
    {
        return $this->oAuthToken;
    }

    public function setFolderId(string $folderId)
    {
        $this->folderId = $folderId;
    }

    /**
     * @return string
     */
    public function getFolderId(): string
    {
        return $this->folderId;
    }

    /**
     * @param string $pathToFile
     * @param string $base64ConvertedImage
     * @param AbstractTemplate $template
     * @param string $lang
     * @return void
     * @throws AccessDeniedException
     * @throws GetTextDetectionException
     * @throws GuzzleException
     */
    public function getDetectedDocument(string $pathToFile, string $base64ConvertedImage, AbstractTemplate $template, string $lang)
    {
        // Формирование запроса для распознавания документа
        $requestParams = [
            'json' => [
                'folderId' => $this->folderId,
                'analyze_specs' => [
                    'content' => $base64ConvertedImage,
                    'features' => [
                        'type' => 'TEXT_DETECTION',
                        'text_detection_config' => [
                            'language_codes' => [
                                $lang
                            ],
                            'model' => $template->getTemplateName(),
                        ],
                    ],
                    "mime_type" => mime_content_type($pathToFile),
                ],
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->IAMToken,
            ],
        ];

        // Запрос на распознавание документа
        try {
            $contents = $this->guzzleClient->request(
                self::TEXT_DETECTION_METHOD,
                self::TEXT_DETECTION_HOST,
                $requestParams
            )
                ->getBody()
                ->getContents();

            $contents = json_decode($contents, true);
        } catch (\Exception $e) {
            throw new GetTextDetectionException($e->getMessage());
        }

        // Проверка, не получили ли ошибок после запроса
        if (isset($contents['code']) && $contents['code'] == AccessDeniedException::ERROR_IAM_TOKEN) {
            throw new AccessDeniedException($contents['message'] ?? 'Token is invalid');
        }
        if (isset($contents['results'][0]['results'][0]['error']['message'])) {
            throw new GetTextDetectionException($contents['results'][0]['results'][0]['error']['message']);
        }

        // Заполнение DTO в зависимости от модели
        if (isset($contents['results'][0]['results'][0]['textDetection']['pages'][0]['entities'])) {
            return ($contents['results'][0]['results'][0]['textDetection']['pages'][0]['entities']);
        }

        return null;
    }
}
