<?php

namespace mfteam\ocrVisionYandexCloud\templates;

use mfteam\ocrVisionYandexCloud\VisionYandexGateway;

/**
 * Фабрика шаблонов ответов
 *
 * Class TemplateFactory
 */
class TemplateFactory
{
    /**
     * @param array $data
     * @param string $model
     * @return DriverLicenseBack|DriverLicenseFront|Passport|void
     */
    public static function templateInstance(array $data, string $model)
    {
        switch ($model) {
            case VisionYandexGateway::MODEL_PASSPORT:
                return new Passport($data);

            case VisionYandexGateway::MODEL_DRIVER_LICENSE_FRONT:
                return new DriverLicenseFront($data);

            case VisionYandexGateway::MODEL_DRIVER_LICENSE_BACK:
                return new DriverLicenseBack($data);
        }
    }
}