<?php

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\exceptions\GetIAMTokenException;
use mfteam\ocrVisionYandexCloud\exceptions\ModelEnumException;
use mfteam\ocrVisionYandexCloud\helpers\FileHelper;
use mfteam\ocrVisionYandexCloud\templates\TemplateFactory;

/**
 * Шлюз взаимодействия с API распознавания текстов Yandex Vision Cloud
 *
 * Class VisionYandexGateway
 * @package mfteam\ocrVisionYandexCloud
 */
class VisionYandexGateway
{
    /**
     * Api клиент
     *
     * @var VisionApiClientInterface
     */
    protected $apiClient;

    /**
     * Клиент работы с файлами
     *
     * @var VisionApiClientInterface
     */
    protected $fileAssist;

    /**
     * Тип распознаваемого документа, допустимые значения:
     *  - passport
     *  - driver-license-front
     *  - driver-license-back
     *
     * @var string $model;
     */
    protected $model = null;

    /**
     * Язык распознавания. По умолчанию: "ru"
     *
     * @var string $lang
     */
    protected $lang;

    protected const DEFAULT_LANG = 'ru';

    public const MODEL_PASSPORT = 'passport';
    public const MODEL_DRIVER_LICENSE_FRONT = 'driver-license-front';
    public const MODEL_DRIVER_LICENSE_BACK = 'driver-license-back';
    public const ALLOWED_MODELS_LIST = [
        self::MODEL_PASSPORT,
        self::MODEL_DRIVER_LICENSE_FRONT,
        self::MODEL_DRIVER_LICENSE_BACK,
    ];

    /**
     * @param VisionApiClientInterface $apiClient
     * @param FileAssistInterface $fileAssist
     * @param string $model
     * @param ?string $lang
     * @throws ModelEnumException
     */
    public function __construct(
        VisionApiClientInterface $apiClient,
        FileAssistInterface $fileAssist,
        string $model,
        ?string $lang
    ) {
        $this->apiClient = $apiClient;
        $this->fileAssist = $fileAssist;
        $this->setModel($model);
        $this->setLang($lang);

        $this->prepareIAMToken();
    }

    /**
     * @return void
     */
    protected function prepareIAMToken()
    {
        // Прочитать токен из файла
        $IAMToken = $this->fileAssist->readAIMToken();

        if ($IAMToken !== null) {
            $IAMToken = explode(PHP_EOL, $IAMToken);

            if (count($IAMToken) === 2 && (int) $IAMToken[0] < time()) {
                $this->apiClient->setAIMToken($IAMToken[1]);

                return;
            }
        }

        // Если не удалось, получить новый токен. Записать новый IAM токен в файл
        $IAMToken = $this->apiClient->getFreshAIMToken();
        $this->apiClient->setAIMToken($IAMToken->getIAMToken());
        $this->fileAssist->writeAIMToken($IAMToken);
    }

    /**
     * @param string $model
     * @return void
     * @throws ModelEnumException
     */
    public function setModel(string $model)
    {
        if (!in_array($model, self::ALLOWED_MODELS_LIST)) {
            throw new ModelEnumException('Given model is not in list of allowed models');
        }

        $this->model = $model;
    }

    public function setLang(?string $lang)
    {
        $this->lang = $lang ? strtolower($lang) : self::DEFAULT_LANG;
    }

    /**
     * @param string $processedFilename
     * @return templates\DriverLicenseBack|templates\DriverLicenseFront|templates\Passport|null
     */
    public function processDetection(string $processedFilename)
    {
        $result = $this->apiClient->getDetectedDocument(
            $this->fileAssist->convertDetectedDocument($processedFilename),
            $this->model,
            $this->lang
        );

        if ($result === null) {
            return null;
        }

        return TemplateFactory::templateInstance($result, $this->model);
    }
}
