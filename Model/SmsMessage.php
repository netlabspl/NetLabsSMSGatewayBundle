<?php

/*
 * This file is part of the NetLabsSMSGatewayBundle package.
 *
 * (c) Michał Jabłoński <m.jablonski@net-labs.pl>
 */

namespace NetLabs\SMSGatewayBundle\Model;

/**
 * SmsMessage
 *
 * @package NetLabs\SMSGatewayBundle\Model
 */
class SmsMessage
{
    private $receiver;

    private $senderName;

    private $content;

    private $isFlash;

    private $isWapPush;

    private $isUTF8;

    private $isVoice;

    private $isVCard;

    private $sendAt;

    private $usmsid;

    public function __toString()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $isFlash
     *
     * @return $this
     */
    public function setIsFlash($isFlash)
    {
        $this->isFlash = $isFlash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsFlash()
    {
        return $this->isFlash;
    }

    /**
     * @param mixed $isUTF8
     *
     * @return $this
     */
    public function setIsUTF8($isUTF8)
    {
        $this->isUTF8 = $isUTF8;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsUTF8()
    {
        return $this->isUTF8;
    }

    /**
     * @param mixed $isVCard
     *
     * @return $this
     */
    public function setIsVCard($isVCard)
    {
        $this->isVCard = $isVCard;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsVCard()
    {
        return $this->isVCard;
    }

    /**
     * @param mixed $isVoice
     *
     * @return $this
     */
    public function setIsVoice($isVoice)
    {
        $this->isVoice = $isVoice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsVoice()
    {
        return $this->isVoice;
    }

    /**
     * @param mixed $isWapPush
     *
     * @return $this
     */
    public function setIsWapPush($isWapPush)
    {
        $this->isWapPush = $isWapPush;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsWapPush()
    {
        return $this->isWapPush;
    }

    /**
     * @param mixed $receiver
     *
     * @return $this
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param mixed $sendAt
     *
     * @return $this
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * @param mixed $usmsid
     *
     * @return $this
     */
    public function setUsmsid($usmsid)
    {
        $this->usmsid = $usmsid;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsmsid()
    {
        return $this->usmsid;
    }

    /**
     * @param mixed $senderName
     *
     * @return $this
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return $this->senderName;
    }
}