<?php

namespace mfteam\ocrVisionYandexCloud\Test\Unit\responses;

use PHPUnit\Framework\TestCase;

class IAMTokenResponseTest extends TestCase
{
    protected $expiredAt = '2033-12-31T12:37:17.1355786';

    public function testGetExpiredAtAsUnixTime()
    {
        $prepareDatetime = str_replace('T', ' ', substr($this->expiredAt, 0 , 19));
        $result = strtotime($prepareDatetime);

        $this->assertEquals(2019645437, $result);
    }
}
