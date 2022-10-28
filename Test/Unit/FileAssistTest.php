<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit;

use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;
use PHPUnit\Framework\TestCase;

class FileAssistTest extends TestCase
{
    protected $IAMFileName;
    protected $IAMFileNameEmpty = null;

    protected function setUp(): void
    {
        $this->IAMFileName = tempnam(sys_get_temp_dir(), 'VisionAnalyzeApi_');
        file_put_contents($this->IAMFileName, time() - 100 . PHP_EOL . 'IAMToken');
    }

    public function testReadAIMToken()
    {
        $result = file_exists($this->IAMFileName);
        $this->assertEquals(true, $result);

        $result = file_exists($this->IAMFileNameEmpty);
        $this->assertEquals(false, $result);

        $result = explode(PHP_EOL, file_get_contents($this->IAMFileName));
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertLessThan(time(), (int) $result[0]);

        $result = new IAMTokenResponse($result[0], $result[1]);
        $this->assertNotEquals(null, $result);
        $this->assertInstanceOf(IAMTokenResponse::class, $result);
    }
}
