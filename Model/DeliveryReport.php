<?php

/*
 * This file is part of the NetLabsSMSGatewayBundle package.
 *
 * (c) Michał Jabłoński <m.jablonski@net-labs.pl>
 */

namespace NetLabs\SMSGatewayBundle\Model;

/**
 * DeliveryReport
 *
 * @package NetLabs\SMSGatewayBundle\Model
 */
class DeliveryReport
{
    private $smsId;

    private $number;

    private $content;

    private $status;

    private $queueAt;

    private $sentAt;

    private $deliveredAt;

    private $reason;

    private $failureAt;

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $deliveredAt
     */
    public function setDeliveredAt($deliveredAt)
    {
        $this->deliveredAt = $deliveredAt;
    }

    /**
     * @return mixed
     */
    public function getDeliveredAt()
    {
        return $this->deliveredAt;
    }

    /**
     * @param mixed $failureAt
     */
    public function setFailureAt($failureAt)
    {
        $this->failureAt = $failureAt;
    }

    /**
     * @return mixed
     */
    public function getFailureAt()
    {
        return $this->failureAt;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $queueAt
     */
    public function setQueueAt($queueAt)
    {
        $this->queueAt = $queueAt;
    }

    /**
     * @return mixed
     */
    public function getQueueAt()
    {
        return $this->queueAt;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return mixed
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param mixed $smsId
     */
    public function setSmsId($smsId)
    {
        $this->smsId = $smsId;
    }

    /**
     * @return mixed
     */
    public function getSmsId()
    {
        return $this->smsId;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}