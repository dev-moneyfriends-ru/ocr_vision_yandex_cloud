<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit\templates;

use mfteam\ocrVisionYandexCloud\templates\Passport;
use PHPUnit\Framework\TestCase;

class PassportTest extends TestCase
{
    protected $items;
    protected $templateName = 'passport';
    protected $data = [
        'name' => 'Some name',
        'middleName' => 'Some middle name',
        'surname' => 'Some surname',
        'gender' => 'mf',
        'citizenship' => 'world',
        'birthDate' => '31.01.1980',
        'birthPlace' => 'USSR',
        'number' => '1211289574',
        'issueDate' => '31.12.1994',
        'subdivision' => '011-001',
        'expirationDate' => '-',
    ];
    protected $mock;

    protected function setUp(): void
    {
        $this->mock = $this->getMockBuilder(Passport::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mock->expects($this->any())
            ->method('getTemplateName')
            ->willReturn($this->templateName);

        $this->items = $this->data;
    }

    public function testGetTemplateName()
    {
        $this->assertEquals('passport', $this->mock->getTemplateName());
    }

    public function testToArray()
    {
        $this->assertIsArray($this->items);

        $this->assertArrayHasKey('name', $this->items);
        $this->assertArrayHasKey('middleName', $this->items);
        $this->assertArrayHasKey('surname', $this->items);
        $this->assertArrayHasKey('gender', $this->items);
        $this->assertArrayHasKey('citizenship', $this->items);
        $this->assertArrayHasKey('birthDate', $this->items);
        $this->assertArrayHasKey('birthPlace', $this->items);
        $this->assertArrayHasKey('number', $this->items);
        $this->assertArrayHasKey('issueDate', $this->items);
        $this->assertArrayHasKey('subdivision', $this->items);
        $this->assertArrayHasKey('expirationDate', $this->items);
    }
}
