<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit\templates;

use mfteam\ocrVisionYandexCloud\templates\DriverLicenseBack;
use PHPUnit\Framework\TestCase;

class DriverLicenseBackTest extends TestCase
{
    protected $items;
    protected $templateName = 'driver-license-back';
    protected $data = [
        'experienceFrom' => '12.05.2023',
        'number' => '1211285947',
        'issueDate' => '12.11.2001',
        'expirationDate' => '12.05.2023',
        'prevNumber' => '1211285947',
    ];

    protected function setUp(): void
    {
        $this->mock = $this->getMockBuilder(DriverLicenseBack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock->expects($this->any())
            ->method('getTemplateName')
            ->willReturn($this->templateName);

        $this->items = $this->data;
    }

    public function testGetTemplateName()
    {
        $this->assertEquals('driver-license-back', $this->mock->getTemplateName());
    }

    public function testToArray()
    {
        $this->assertIsArray($this->items);

        $this->assertArrayHasKey('experienceFrom', $this->items);
        $this->assertArrayHasKey('number', $this->items);
        $this->assertArrayHasKey('issueDate', $this->items);
        $this->assertArrayHasKey('expirationDate', $this->items);
        $this->assertArrayHasKey('prevNumber', $this->items);
    }
}
