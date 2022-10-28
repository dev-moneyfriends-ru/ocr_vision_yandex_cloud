<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit\helpers;

use PHPUnit\Framework\TestCase;

class FileHelperTest extends TestCase
{
    protected $IAMFileName = '/tmp/VisionAnalyzeApi_AUG9OI.jpg';
    protected $IAMFileNameEmpty = null;
    protected $tempFile;
    protected $allowedExtensions = [
        'jpg',
        'png',
        'pdf',
    ];

    protected function setUp(): void
    {
        $this->tempFile = tempnam(sys_get_temp_dir(), 'VisionAnalyzeApi_');
    }

    public function testReadAIMToken()
    {
        $result = tempnam(sys_get_temp_dir(), 'VisionAnalyzeApi_');

        $this->assertNotEmpty($result);
    }

    public function testGetExtension()
    {
        $result = pathinfo(strtolower($this->IAMFileNameEmpty))['extension'] ?? null;

        $this->assertEmpty($result);

        $result = pathinfo(strtolower($this->IAMFileName))['extension'] ?? null;

        $this->assertStringEndsWith('jpg', $result);
    }

    public function testCheckProcessedFile()
    {
        $result = !file_exists($this->IAMFileName);

        $this->assertEquals(true, $result);

        file_put_contents($this->tempFile, 'some string less 1Mb');
        $result = filesize($this->tempFile) > 1048576;

        $this->assertEquals(false, $result);

        $result = !in_array('jpg', $this->allowedExtensions);

        $this->assertEquals(false, $result);
    }
}
