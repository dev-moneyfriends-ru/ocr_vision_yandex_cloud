<?php

namespace mfteam\ocrVisionYandexCloud;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use mfteam\ocrVisionYandexCloud\Dto\DriverLicenseBack;
use mfteam\ocrVisionYandexCloud\Dto\DriverLicenseFront;
use mfteam\ocrVisionYandexCloud\exceptions\AccessDeniedException;
use mfteam\ocrVisionYandexCloud\Dto\Passport;
use mfteam\ocrVisionYandexCloud\exceptions\GetIAMTokenException;
use mfteam\ocrVisionYandexCloud\exceptions\GetTextDetectionException;
use mfteam\ocrVisionYandexCloud\exceptions\IAMFileException;
use mfteam\ocrVisionYandexCloud\exceptions\ImageFileException;
use mfteam\ocrVisionYandexCloud\exceptions\ModelEnumException;

/**
 * Компонент для запросов к сервису Vision Yandex Cloud
 */
class ApiClient
{
    /**
     * @var string
     */
    private $host = 'https://vision.api.cloud.yandex.net/vision/v1/batchAnalyze';

    /**
     * @var string
     */
    private $method = 'POST';

    /**
     * @var string
     */
    private $IAMHost = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';

    /**
     * @var string
     */
    private $IAMMethod = 'POST';

    /**
     * Имя файла для хранения IAM токена
     */
    private const IAM_TOKEN_FILENAME = 'data.key';
    
    /**
     * @var string ключ для аутентификации
     */
    private $IAMToken;
    
    /**
     * @var string Идентификатор каталога
     */
    private $folderId;
    
    /**
     * @var string модель для перевода, возможны значения {passport, driver-license-front, driver-license-back}
     */
    private $model;

    /**
     * @var string язык на котором будет идти распознавание
     */
    private $lang = 'ru';

    private $enumModels = [
        'passport',
        'driver-license-front',
        'driver-license-back',
    ];

    /**
     * @param string $oAuthToken
     * @param string $folderId
     * @param string $model
     * @param string $lang
     * @throws GetIAMTokenException
     * @throws GuzzleException
     * @throws IAMFileException
     * @throws ModelEnumException
     */
    public function __construct(
        string $oAuthToken,
        string $folderId,
        string $model = 'passport',
        string $lang = 'ru'
    )
    {
        $this->setIAMToken($oAuthToken);
        $this->folderId = $folderId;
        $this->setModel($model);
        $this->lang = $lang;
    }

    /**
     * @param string $model
     * @return void
     * @throws ModelEnumException
     */
    public function setModel(string $model)
    {
        if (!in_array($model, $this->enumModels)) {
            throw new ModelEnumException('Given model is not in list of allowed models');
        }

        $this->model = $model;
    }

    /**
     * @param string $host
     * @return void
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @param string $method
     * @return void
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @param string $IAMHost
     * @return void
     */
    public function setIAMHost(string $IAMHost)
    {
        $this->IAMHost = $IAMHost;
    }

    /**
     * @param string $IAMMethod
     * @return void
     */
    public function setIAMMethod(string $IAMMethod)
    {
        $this->IAMMethod = $IAMMethod;
    }

    /**
     * В файле IAM_TOKEN_FILENAME в первой строке в unix-тайм лежит время до которого работает токен, во второй токен.
     *
     * @param string $oAuthToken
     * @return void
     * @throws GetIAMTokenException
     * @throws GuzzleException
     * @throws IAMFileException
     */
    private function setIAMToken(string $oAuthToken)
    {
        $fileName = __DIR__ . '/' . self::IAM_TOKEN_FILENAME;

        // Получить IAM токен из файла, если он актуален
        try {
            if (file_exists($fileName)) {
                $fileData = explode(PHP_EOL, file_get_contents($fileName));

                if (count($fileData) === 2 && (int) $fileData[0] < time()) {
                    $this->IAMToken = $fileData[1];

                    return;
                }
            }
        } catch (Exception $e) {
            throw new IAMFileException($e->getMessage());
        }

        // Получить свежий IAM токен
        try {
            $contents = (new Client())->request(
                $this->IAMMethod,
                $this->IAMHost,
                ['json' => ['yandexPassportOauthToken' => $oAuthToken]]
            )
                ->getBody()
                ->getContents();
            $contents = json_decode($contents, true);

            if (!isset($contents['iamToken']) || !isset($contents['expiresAt'])) {
                throw new GetIAMTokenException('Wrong response while getting IAM Token.');
            }
        } catch (Exception $e) {
            throw new GetIAMTokenException($e->getMessage());
        }

        // Записать новый токен в файл
        $prepareDatetime = str_replace('T', ' ', substr($contents['expiresAt'], 0 , 19));
        file_put_contents($fileName, strtotime($prepareDatetime) . PHP_EOL . $contents['iamToken']);

        $this->IAMToken = $contents['iamToken'];
    }

    /**
     * @param string $imageNameFullPath
     * @return array
     * @throws AccessDeniedException
     * @throws GetTextDetectionException
     * @throws GuzzleException
     * @throws ImageFileException
     */
    public function processDetection(string $imageNameFullPath): array
    {
        // Проверка и подготовка распознаваемого документа
        if (!file_exists($imageNameFullPath)) {
            throw new ImageFileException('Image file does not exists');
        }
        if (filesize($imageNameFullPath) > 1048576) {
            throw new ImageFileException('Image file must be less 1 Mb');
        }
        try {
            $image = base64_encode(file_get_contents($imageNameFullPath));
        } catch (Exception $e) {
            throw new ImageFileException($e->getMessage());
        }

        // Формирование запроса для распознавания документа
        $requestParams = [
            'json' => [
                'folderId' => $this->folderId,
                'analyze_specs' => [
                    'content' => $image,
                    'features' => [
                        'type' => 'TEXT_DETECTION',
                        'text_detection_config' => [
                            'language_codes' => [
                                $this->lang
                            ],
                            'model' => $this->model,
                        ],
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->IAMToken,
            ],
        ];

        // Запрос на распознавание документа
        try {
            $contents = (new Client())->request(
                $this->method,
                $this->host,
                $requestParams
            )
            ->getBody()
            ->getContents();

            $contents = json_decode($contents, true);
        } catch (Exception $e) {
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
        $results = [];
        if (isset($contents['results'][0]['results'][0]['textDetection']['pages'])) {
            foreach ($contents['results'][0]['results'][0]['textDetection']['pages'] as $page) {
                switch ($this->model) {
                    case 'passport':
                        $results[] = new Passport($page['entities']);
                        break;
                    case 'driver-license-front':
                        $results[] = new DriverLicenseFront($page['entities']);
                        break;
                    case 'driver-license-back':
                        $results[] = new DriverLicenseBack($page['entities']);
                }
            }
        }

        return $results;
    }
}
