<?php

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Данные в фабрике не шаблоне заполнены
 */
class FillTemplateException extends \Exception
{
    public function getName()
    {
        return 'FillTemplate';
    }
}
