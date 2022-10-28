<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit;

use mfteam\ocrVisionYandexCloud\FileAssist;
use mfteam\ocrVisionYandexCloud\templates\AbstractTemplate;
use mfteam\ocrVisionYandexCloud\VisionApiClient;
use mfteam\ocrVisionYandexCloud\VisionYandexGateway;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VisionYandexGatewayFactoryTest extends TestCase
{
    /**
     * @var VisionApiClient|MockObject
     */
    protected $clientApi;

    /**
     * @var FileAssist|MockObject
     */
    protected $fileAssist;

    protected function setUp(): void
    {
        $this->clientApi = $this->getMockBuilder(VisionApiClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fileAssist = $this->getMockBuilder(FileAssist::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testInstanceClient()
    {
        $template = $this->createMock(AbstractTemplate::class);

        $result = new VisionYandexGateway($this->clientApi, $this->fileAssist, $template);

        $this->assertInstanceOf(VisionYandexGateway::class, $result);
    }
}
