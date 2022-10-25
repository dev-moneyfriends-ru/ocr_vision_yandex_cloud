<?php

namespace mfteam\ocrVisionYandexCloud\templates;

class DriverLicenseBack extends AbstractTemplate
{
    protected $experience_from;
    protected $number;
    protected $issue_date;
    protected $expiration_date;
    protected $prev_number;

    /**
     * @param array $data Результат распознавания обратной стороны водительских прав
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function setItems(array $data = [])
    {
        foreach ($data as $item) {
            if (isset($item['name']) && isset($item['text'])) {
                $this->{$item['name']} = $item['text'];
            }
        }
    }

    public function experienceFrom(): string
    {
        return $this->experience_from;
    }

    /**
     * @return string
     */
    public function number(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function issueDate(): string
    {
        return $this->issue_date;
    }

    /**
     * @return string
     */
    public function expirationDate(): string
    {
        return $this->expiration_date;
    }

    /**
     * @return string
     */
    public function prevNumber(): string
    {
        return $this->prev_number;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'experienceFrom' => $this->experience_from,
            'number' => $this->number,
            'issueDate' => $this->issue_date,
            'expirationDate' => $this->expiration_date,
            'prevNumber' => $this->prev_number,
        ];
    }
}
