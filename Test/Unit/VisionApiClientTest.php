<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit;

use GuzzleHttp\Client;
use mfteam\ocrVisionYandexCloud\exceptions\AccessDeniedException;
use mfteam\ocrVisionYandexCloud\responses\IAMTokenResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class VisionApiClientTest extends TestCase
{
    protected $client;
    protected $IAMTokenJson = '{"iamToken": "iamtoken1654684", "expiresAt": "2023-10-28T13:20:17.8465131"}';
    protected $IAMJsonEmpty;
    protected $textDetection = '{"results":[{"results":[{"textDetection":{"pages":[{"entities": "Detected"}]}}]}]}';
    protected $textDetectionErrorAccess = '{"code": ' . AccessDeniedException::ERROR_IAM_TOKEN . '}';
    protected $textDetectionError = '{"results":[{"results":[{"error":{"message": "Something goes wrong"}}]}]}';

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDetectedDocument()
    {
        $this->client->expects($this->any())
            ->method('request');

        $request = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->any())
            ->method('getBody')
            ->willReturn(StreamInterface::class);

        $streamInterface = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamInterface->expects($this->any())
            ->method('getContents')
            ->will(
                $this->returnValue('{"iamToken": "iamtoken1654684", "expiresAt": "2023-10-28T13:20:17.8465131"}')
            );

        $this->assertEquals(
            '{"iamToken": "iamtoken1654684", "expiresAt": "2023-10-28T13:20:17.8465131"}',
            $this->IAMTokenJson
        );

        $contents = json_decode($this->IAMTokenJson, true);

        $this->assertIsArray($contents);
        $this->assertArrayHasKey('iamToken', $contents);
        $this->assertArrayHasKey('expiresAt', $contents);

        $request = new IAMTokenResponse($contents['iamToken'], $contents['expiresAt']);
        $this->assertInstanceOf(IAMTokenResponse::class, $request);
    }

    public function testGetFreshAIMToken()
    {
        $this->client->expects($this->any())
            ->method('request');

        $request = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->any())
            ->method('getBody')
            ->willReturn(StreamInterface::class);

        $streamInterface = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamInterface->expects($this->any())
            ->method('getContents')
            ->will(
                $this->returnValue($this->textDetectionErrorAccess)
            );

        $contents = json_decode($this->textDetectionErrorAccess, true);
        $this->assertIsArray($contents);
        $this->assertArrayHasKey('code', $contents);
        $this->assertEquals(AccessDeniedException::ERROR_IAM_TOKEN, $contents['code']);

        $contents = json_decode($this->textDetectionError, true);
        $this->assertIsArray($contents);
        $this->assertArrayHasKey('results', $contents);
        $this->assertArrayHasKey('results', $contents['results'][0]);
        $this->assertArrayHasKey('error', $contents['results'][0]['results'][0]);
        $this->assertArrayHasKey('message', $contents['results'][0]['results'][0]['error']);
        $this->assertEquals('Something goes wrong', $contents['results'][0]['results'][0]['error']['message']);

        $contents = json_decode($this->textDetection, true);
        $this->assertIsArray($contents);
        $this->assertArrayHasKey('results', $contents);
        $this->assertArrayHasKey('results', $contents['results'][0]);
        $this->assertArrayHasKey('textDetection', $contents['results'][0]['results'][0]);
        $this->assertArrayHasKey('pages', $contents['results'][0]['results'][0]['textDetection']);
        $this->assertArrayHasKey('entities', $contents['results'][0]['results'][0]['textDetection']['pages'][0]);
    }
}
