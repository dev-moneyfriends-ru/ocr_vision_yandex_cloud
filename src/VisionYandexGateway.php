<?php

declare(strict_types=1);

namespace  mfteam\ocrVisionYandexCloud;

use mfteam\ocrVisionYandexCloud\exceptions\FillTemplateException;
use mfteam\ocrVisionYandexCloud\templates\AbstractTemplate;

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
     * @var FileAssistInterface
     */
    protected $fileAssist;

    /** Шаблон распознаваемого документа, имеющиеся типы:
     *  - passport
     *  - driver-license-front
     *  - driver-license-back
     *
     * @var AbstractTemplate $template
     */
    protected $template;

    /**
     * Язык распознавания. По умолчанию: "ru"
     *
     * @var string $lang
     */
    protected $lang = self::DEFAULT_LANG;
    protected const DEFAULT_LANG = 'ru';

    /**
     * @param VisionApiClientInterface $apiClient
     * @param FileAssistInterface $fileAssist
     * @param AbstractTemplate $template
     */
    public function __construct(
        VisionApiClientInterface $apiClient,
        FileAssistInterface $fileAssist,
        AbstractTemplate $template
    ) {
        $this->apiClient = $apiClient;
        $this->fileAssist = $fileAssist;
        $this->template = $template;

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
            $this->apiClient->setAIMToken($IAMToken);
        } else {
            // Если не удалось, получить новый токен. Записать новый IAM токен в файл
            $IAMToken = $this->apiClient->getFreshAIMToken();
            $this->apiClient->setAIMToken($IAMToken->getIAMToken());
            $this->fileAssist->writeAIMToken($IAMToken);
        }
    }

    public function setLang(?string $lang)
    {
        $this->lang = $lang ? strtolower($lang) : self::DEFAULT_LANG;
    }

    /**
     * @param string $processedFilename
     * @return AbstractTemplate
     * @throws FillTemplateException
     */
    public function processDetection(string $processedFilename): AbstractTemplate
    {
        $result = $this->apiClient->getDetectedDocument(
            $processedFilename,
            $this->fileAssist->convertDetectedDocument($processedFilename),
            $this->template,
            $this->lang
        );

        if ($result === null) {
            throw new FillTemplateException('Empty value in template');
        }

        $this->template->setItems($result);
        return $this->template;
    }
}
