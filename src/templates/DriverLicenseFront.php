<?php

namespace mfteam\ocrVisionYandexCloud\templates;

class DriverLicenseFront extends AbstractTemplate
{
    protected $templateName = 'driver-license-front';

    protected $name;
    protected $middle_name;
    protected $surname;
    protected $number;
    protected $birth_date;
    protected $issue_date;
    protected $expiration_date;

    /**
     * @return string
     */
    public function getTemplateName(): string
    {
        return parent::getTemplateName();
    }

    /**
     * @param array $data
     * @return void
     */
    public function setItems(array $data = [])
    {
        foreach ($data as $item) {
            if (isset($item['name']) && isset($item['text'])) {
                $this->{$item['name']} = $item['text'];
            }
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return $this->birth_date;
    }

    /**
     * @return string
     */
    public function getIssueDate(): string
    {
        return $this->issue_date;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expiration_date;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'middleName' => $this->middle_name,
            'surname' => $this->surname,
            'number' => $this->number,
            'birthDate' => $this->birth_date,
            'issueDate' => $this->issue_date,
            'expirationDate' => $this->expiration_date,
        ];
    }
}
