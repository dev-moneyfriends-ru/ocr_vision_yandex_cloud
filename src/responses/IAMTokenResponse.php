<?php

namespace mfteam\ocrVisionYandexCloud\responses;

class IAMTokenResponse
{
    /**
     * @var $IAMToken string
     */
    protected $IAMToken;

    /**
     * @var $expiredAt string
     */
    protected $expiredAt;

    /**
     * @param string $IAMToken
     * @param string $expiredAt
     */
    public function __construct(string $IAMToken, string $expiredAt)
    {
        $this->setAIMToken($IAMToken);
        $this->setExpiredAt($expiredAt);
    }

    /**
     * @return string
     */
    public function getIAMToken(): string
    {
        return $this->IAMToken;
    }

    /**
     * @param string $IAMToken
     * @return void
     */
    public function setAIMToken(string $IAMToken)
    {
        $this->IAMToken = $IAMToken;
    }

    /**
     * @return string
     */
    public function getExpiredAt(): string
    {
        return $this->expiredAt;
    }

    /**
     * @param string $expiredAt
     * @return void
     */
    public function setExpiredAt(string $expiredAt)
    {
        $this->IAMToken = $expiredAt;
    }

    /**
     * @return false|int
     */
    public function getExpiredAtAsUnixTime()
    {
        $prepareDatetime = str_replace('T', ' ', substr($this->expiredAt, 0 , 19));

        return strtotime($prepareDatetime);
    }
}