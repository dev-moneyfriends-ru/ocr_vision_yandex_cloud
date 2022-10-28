<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit\templates;

use mfteam\ocrVisionYandexCloud\templates\DriverLicenseFront;
use PHPUnit\Framework\TestCase;

class DriverLicenseFrontTest extends TestCase
{
    protected $items;
    protected $templateName = 'driver-license-front';
    protected $data = [
        'name' => 'some name',
        'middleName' => 'middle name',
        'surname' => 'surname',
        'number' => '1211285947',
        'birthDate' => '01.01.1980',
        'issueDate' => '12.05.2013',
        'expirationDate' => '12.05.2023',
    ];
    protected $mock;

    protected function setUp(): void
    {
        $this->mock = $this->getMockBuilder(DriverLicenseFront::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock->expects($this->any())
            ->method('getTemplateName')
            ->willReturn($this->templateName);

        $this->items = $this->data;
    }

    public function testGetTemplateName()
    {
        $this->assertEquals('driver-license-front', $this->mock->getTemplateName());
    }

    public function testToArray()
    {
        $this->assertIsArray($this->items);

        $this->assertArrayHasKey('name', $this->items);
        $this->assertArrayHasKey('middleName', $this->items);
        $this->assertArrayHasKey('surname', $this->items);
        $this->assertArrayHasKey('number', $this->items);
        $this->assertArrayHasKey('birthDate', $this->items);
        $this->assertArrayHasKey('issueDate', $this->items);
        $this->assertArrayHasKey('expirationDate', $this->items);
    }
}
