<?php

namespace mfteam\ocrVisionYandexCloud\templates;

/**
 * Абстрактный класс шаблона результата перевода
 */
class AbstractTemplate
{
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setItems($data);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function setItems(array $data = []) {}

    /**
     * Список элементов в массив
     */
    public function toArray() {}
}
