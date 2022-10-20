<?php

namespace mfteam\ocrVisionYandexCloud\Dto;

/**
 * Результат обработки паспорта
 */
class Passport
{
    public $name;
    public $middle_name;
    public $surname;
    public $gender;
    public $citizenship;
    public $birth_date;
    public $birth_place;
    public $number;
    public $issue_date;
    public $subdivision;
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
            'gender' => $this->gender,
            'citizenship' => $this->citizenship,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'number' => $this->number,
            'issue_date' => $this->issue_date,
            'subdivision' => $this->subdivision,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
