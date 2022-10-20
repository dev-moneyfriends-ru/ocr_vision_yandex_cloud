<?php

namespace mfteam\ocrVisionYandexCloud\exceptions;

/**
 * Допустимы только определённые модели для распознавания
 */
class ModelEnumException extends \Exception
{
    public function getName()
    {
        return 'ModelEnum';
    }
}