<?php

namespace mfteam\ocrVisionYandexCloud\Dto;

/**
 * Результат обработки обратной стороны водительского удостоверения
 */
class DriverLicenseBack
{
    public $experience_from;
    public $number;
    public $issue_date;
    public $expiration_date;
    public $prev_number;

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
            'experience_from' => $this->experience_from,
            'number' => $this->number,
            'issue_date' => $this->issue_date,
            'expiration_date' => $this->expiration_date,
            'prev_number' => $this->prev_number,
        ];
    }
}
