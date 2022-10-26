<?php

declare(strict_types=1);

namespace mfteam\ocrVisionYandexCloud\templates;

/**
 * Абстрактный класс шаблона результата перевода
 */
class AbstractTemplate
{
    protected $templateName;

    public function __construct() {}

    /**
     * @param array $data
     *
     * @return void
     */
    public function setItems(array $data = []) {}

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * Список элементов в массив
     *
     * @return array
     */
    public function toArray(): array {}
}
