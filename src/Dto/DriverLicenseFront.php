<?php

namespace mfteam\ocrVisionYandexCloud\Dto;

/**
 * Результат обработки лицевой стороны водительского удостоверения
 */
class DriverLicenseFront
{
    public $name;
    public $middle_name;
    public $surname;
    public $number;
    public $birth_date;
    public $issue_date;
    public $expiration_date;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $item) {
            if (isset($item['name']) && isset($item['text'])) {
                $this->{$item['name']} = $item['text'];
            }
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'surname' => $this->surname,
            'number' => $this->number,
            'birth_date' => $this->birth_date,
            'issue_date' => $this->issue_date,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
