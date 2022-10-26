<?php

namespace mfteam\ocrVisionYandexCloud\templates;

class Passport extends AbstractTemplate
{
    protected $templateName = 'passport';

    protected $name;
    protected $middle_name;
    protected $surname;
    protected $gender;
    protected $citizenship;
    protected $birth_date;
    protected $birth_place;
    protected $number;
    protected $issue_date;
    protected $subdivision;
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
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return (string) $this->middle_name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return (string) $this->surname;
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return (string) $this->gender;
    }

    /**
     * @return string
     */
    public function getCitizenship(): string
    {
        return (string) $this->citizenship;
    }

    /**
     * @return string
     */
    public function getBirthDate(): string
    {
        return (string) $this->birth_date;
    }

    /**
     * @return string
     */
    public function getBirthPlace(): string
    {
        return (string) $this->birth_place;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return (string) $this->number;
    }

    /**
     * @return string
     */
    public function getIssueDate(): string
    {
        return (string) $this->issue_date;
    }

    /**
     * @return string
     */
    public function getSubdivision(): string
    {
        return (string) $this->subdivision;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return (string) $this->expiration_date;
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
            'gender' => $this->gender,
            'citizenship' => $this->citizenship,
            'birthDate' => $this->birth_date,
            'birthPlace' => $this->birth_place,
            'number' => $this->number,
            'issueDate' => $this->issue_date,
            'subdivision' => $this->subdivision,
            'expirationDate' => $this->expiration_date,
        ];
    }
}
