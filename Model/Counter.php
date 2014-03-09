<?php

/*
 * This file is part of the NetLabsSMSGatewayBundle package.
 *
 * (c) Michał Jabłoński <m.jablonski@net-labs.pl>
 */

namespace NetLabs\SMSGatewayBundle\Model;

/**
 * Messages limits
 *
 * @package NetLabs\SMSGatewayBundle\Model
 */
class Counter
{
    private $eco;

    private $full;

    private $voice;

    private $mms;

    private $hlr;

    /**
     * @param mixed $eco
     */
    public function setEco($eco)
    {
        $this->eco = $eco;
    }

    /**
     * @return mixed
     */
    public function getEco()
    {
        return $this->eco;
    }

    /**
     * @param mixed $full
     */
    public function setFull($full)
    {
        $this->full = $full;
    }

    /**
     * @return mixed
     */
    public function getFull()
    {
        return $this->full;
    }

    /**
     * @param mixed $hlr
     */
    public function setHlr($hlr)
    {
        $this->hlr = $hlr;
    }

    /**
     * @return mixed
     */
    public function getHlr()
    {
        return $this->hlr;
    }

    /**
     * @param mixed $mms
     */
    public function setMms($mms)
    {
        $this->mms = $mms;
    }

    /**
     * @return mixed
     */
    public function getMms()
    {
        return $this->mms;
    }

    /**
     * @param mixed $voice
     */
    public function setVoice($voice)
    {
        $this->voice = $voice;
    }

    /**
     * @return mixed
     */
    public function getVoice()
    {
        return $this->voice;
    }
}