<?php

/*
 * This file is part of the NetLabsSMSGatewayBundle package.
 *
 * (c) Michał Jabłoński <m.jablonski@net-labs.pl>
 */

namespace NetLabs\SMSGatewayBundle\Service;

use NetLabs\SMSGatewayBundle\Model\Counter;
use NetLabs\SMSGatewayBundle\Model\DeliveryReport;
use NetLabs\SMSGatewayBundle\Model\ReceivedSmsMessage;
use NetLabs\SMSGatewayBundle\Model\SmsMessage;
use Symfony\Component\DependencyInjection\Container;

/**
 * API Connector
 *
 * @package NetLabs\SMSGatewayBundle\Service
 */
class Connector
{
    /**
     * @var Container Service Container
     */
    private $container;

    /**
     * @var string serwersms.pl API URL
     */
    private $apiUrl;

    /**
     * @var bool If true, SMS messages will not be delivered
     */
    private $isTest = false;

    /**
     * @var array serwersms.pl account data
     */
    private $accountData;

    /**
     * @param $container
     */
    public function __construct($container)
    {
        $this->container = $container;

        $this->apiUrl = $this->container->getParameter('net_labs_sms.api.url');
        $this->accountData['login'] = $this->container->getParameter('net_labs_sms.api.username');
        $this->accountData['haslo'] = $this->container->getParameter('net_labs_sms.api.password');
    }

    /**
     * Sends an API call to serwersms.pl API.
     *
     * @param $action API action name
     * @param $parameters API action parameters
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function apiCall($action, $parameters)
    {
        $parameters["akcja"] = $action;
        $parameters["test"] = $this->isTest;
        $postParams = array_merge($this->accountData, $parameters);

        $curl = curl_init($this->apiUrl);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception('SerwerSMS API communication error: ' . curl_error($curl) . ' - ' . curl_errno($curl));
        }

        curl_close($curl);

        return $this->parseResponse($response);
    }

    /**
     * Converts serwersms.pl API response to SimpleXMLElement.
     *
     * @param $response
     *
     * @throws \Exception
     *
     * @return \SimpleXMLElement
     */
    private function parseResponse($response)
    {
        $xml = simplexml_load_string($response);

        if (isset($xml->Blad)) {
            throw new \Exception('SMS API Error: '. (string) $xml->Blad);
        }

        return $xml;
    }

    /**
     * Sends an SMS message.
     *
     * @param \NetLabs\SMSGatewayBundle\Model\SmsMessage $message Message content
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function send(SmsMessage $message)
    {
        if (!$message->getReceiver()) {
            throw new \Exception('Message must have a receiver.');
        }

        $params['numer'] = $message->getReceiver();

        if (!$message->getContent()) {
            throw new \Exception('Message mush have a content.');
        }

        $params['wiadomosc'] = $message->getContent();

        if ($message->getSenderName()) {
            $params['nadawca'] = $message->getSenderName();
        }

        $params['flash'] = $message->getIsFlash();
        $params['wap_push'] = $message->getIsWapPush();
        $params['glosowy'] = $message->getIsVoice();
        $params['vcard'] = $message->getIsVCard();

        if ($message->getIsUTF8()) {
            $params['kodowanie'] = 'UTF-8';
        }

        if ($message->getSendAt()) {
            $params['data_wysylki'] = $message->getSendAt()->format('Y-m-d H:i:s');
        }

        if ($message->getUsmsid()) {
            $params['usmsid'] = $message->getUsmsid();
        }

        $response = $this->apiCall("wyslij_sms", $params);

        $sheduledMessages = array();

        if (count($response)) {
            foreach ($response as $item) {
                $sheduledMessages[] = $item;
            }
        }

        return $response;
    }

    /**
     * Receives message delivery reports.
     *
     * @param string $number Phone number
     * @param \DateTime $startDate Start date (must be provided with endDate)
     * @param \DateTime $endDate End date (must be provided with startDate)
     * @param string $smsId SMS ID
     * @param string $usmsid Message custom identifier
     *
     * @throws \Exception
     *
     * @return array The array of retrieved reports
     */
    public function getReports($number = null, $startDate = null, $endDate = null, $smsId = null, $usmsid = null)
    {
        $parameters = array();

        if ($number) {
            $parameters['numer'] = $number;
        }

        if ($startDate and !$endDate or !$startDate and $endDate) {
            throw new \Exception('Start date and end date must be filled both or none.');
        }

        if ($startDate) {
            $parameters['data_od'] = $startDate->format('Y-m-d H:i:s');
        }

        if ($endDate) {
            $parameters['data_do'] = $endDate->format('Y-m-d H:i:s');
        }

        if ($smsId) {
            $parameters['smsid'] = $smsId;
        }

        if ($usmsid) {
            $parameters['usmsid'] = $usmsid;
        }

        $response = $this->apiCall("sprawdz_sms", $parameters);

        $deliveryReports = array();

        if (count($response)) {
            foreach ($response->SMS as $responseReport) {
                $deliveryReport = new DeliveryReport();
                $deliveryReport->setSmsId((string) $responseReport['id']);
                $deliveryReport->setNumber((string) $responseReport['numer']);
                $deliveryReport->setContent((string) $responseReport['tresc']);
                $deliveryReport->setStatus((string) $responseReport['stan']);
                $deliveryReport->setQueueAt((string) $responseReport['godzina_skolejkowania']);
                $deliveryReport->setSentAt((string) $responseReport['godzina_wyslania']);
                $deliveryReport->setDeliveredAt((string) $responseReport['godzina_doreczenia']);
                $deliveryReport->setReason((string) $responseReport['przyczyna']);
                $deliveryReport->setFailureAt((string) $responseReport['godzina_niedoreczenia']);

                $deliveryReports[] = $deliveryReport;
            }
        }

        return $deliveryReports;
    }

    /**
     * Receives messages limit.
     *
     * @return Counter
     */
    public function getCounters()
    {
        $response = $this->apiCall("ilosc_sms", array());

        $counter = new Counter();
        $counter->setEco((string) $response->SMS[0]);
        $counter->setFull((string) $response->SMS[1]);
        $counter->setVoice((string) $response->SMS[2]);
        $counter->setMms((string) $response->SMS[3]);
        $counter->setHlr((string) $response->SMS[4]);

        return $counter;
    }

    /**
     * Receives incoming SMS messages.
     *
     * @param bool $setAsRead
     * @param null $number
     * @param null $startDate
     * @param null $endDate
     * @param null $type
     * @param null $ndi
     * @param bool $getSmsId
     *
     * @return array
     */
    public function receive($setAsRead = true, $number = null, $startDate = null, $endDate = null, $type = null, $ndi = null, $getSmsId = false)
    {
        $parameters = array();

        if ($number) {
            $parameters['numer'] = $number;
        }

        if ($startDate) {
            $parameters['data_od'] = $startDate->format('Y-m-d H:i:s');
        }

        if ($endDate) {
            $parameters['data_do'] = $endDate->format('Y-m-d H:i:s');
        }

        if ($type) {
            $parameters['typ'] = $type;
        }

        if ($ndi) {
            $parameters['ndi'] = $ndi;
        }

        $parameters['odczyt'] = $setAsRead;
        $parameters['pokaz_powiazanie'] = $getSmsId;

        $response = $this->apiCall("sprawdz_odpowiedzi", $parameters);

        $receivedMessages = array();

        if (count($response)) {
            foreach ($response->SMS as $responseMessage) {
                $receivedMessage = new ReceivedSmsMessage();
                $receivedMessage->setId((string) $responseMessage['id']);
                $receivedMessage->setNumber((string) $responseMessage['numer']);
                $receivedMessage->setDate((string) $responseMessage['data']);
                $receivedMessage->setContent((string) $responseMessage['tresc']);
                $receivedMessage->setSmsId((string) $responseMessage['smsid']);

                $receivedMessages[] = $receivedMessage;
            }
        }

        return $receivedMessages;
    }

    public function hlr($number)
    {
        $response = $this->apiCall("hlr", array('numer' => $number));

        return array(
            'number' => (string) $response->NUMER['numer'],
            'status' => (string) $response->NUMER->status,
            'imsi' => (string) $response->NUMER->imsi,
            'mainNetwork' => (string) $response->NUMER->siec_macierzysta,
            'transferred' => (boolean) $response->NUMER->przenoszony,
            'currentNetwork' => (string) $response->NUMER->siec_obecna
        );
    }

    /**
     * Creates new SMS message.
     *
     * @param string $receiver Receiver's phone number
     * @param string $content Message
     * @param string $senderName Sender's name
     *
     * @return SmsMessage
     */
    public function compose($receiver = null, $content = null, $senderName = null)
    {
        $message = new SmsMessage();
        $message->setReceiver($receiver);
        $message->setContent($content);
        $message->setSenderName($senderName);

        return $message;
    }

    /**
     * @param boolean $isTest
     */
    public function setIsTest($isTest)
    {
        $this->isTest = $isTest;
    }

    /**
     * @return boolean
     */
    public function getIsTest()
    {
        return $this->isTest;
    }
}