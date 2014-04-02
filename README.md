# NetLabsSMSGatewayBundle
---

SMS Gateway for serwersms.pl API

## Installation
---

* composer.json

```json
"require": {
    "netlabs/sms-gateway-bundle": "1.0.*"
}
```

* app/AppKernel.php

```php
new NetLabs\SMSGatewayBundle\NetLabsSMSGatewayBundle()
```


## Configuration
---

* app/config/config.yml

```yaml
net_labs_sms_gateway:
    # serwersms.pl
    api:
        username: testuser
        password: testpassword
```


## Basic Usage
---

#### Sending SMS message

```php
$smsService = $this->get('net_labs_sms');

$message = $smsService->compose()
    ->setReceiver('123123123')
    ->setContent('This is a test message.');

$result = $smsService->send($message);
```


#### Receiving incoming SMS messages

```
$smsService = $this->get('net_labs_sms');

$messages = $smsService->receive();
```

#### Receiving messages delivery reports

```
$smsService = $this->get('net_labs_sms');

$reports = $smsService->getReports();
```

#### Checking messages limit counters

```
$smsService = $this->get('net_labs_sms');

$counters = $smsService->getCounters();
```

#### HLR - Checking main and current networks for phone number

```
$smsService = $this->get('net_labs_sms');

$result = $smsService->hlr('123123123');
```


## Advanced Usage
---

#### Sending SMS message

```php
$smsService = $this->get('net_labs_sms');

$message = $smsService->compose()
    ->setReceiver('123123123')              // receiver's phone number
    ->setContent('This is a test message.') // message content
    ->setSenderName('Test Name');           // max 11 chars
    ->setIsFlash(false)                     // send as flash message - directly to the screen
    ->setIsWapPush(false)                   // send as WapPush message
    ->setIsUTF8(false)                      // set encoding to utf8, so you can use for example polish characters (ąśćźńęółż)
    ->setIsVCard(false)                     // send as VCard message
    ->setSentAt(new \DateTime('+1 hour'))   // set date for delayed delivery
    ->setUsmsid(uniqid());                  // custom unique message ID

$result = $smsService->send($message);
```

#### Receiving incoming SMS messages

```
$smsService = $this->get('net_labs_sms');

$messages = $smsService->receive(
    $setAsRead, // sets message as read after receiving, so it won't be received next time
    $number,    // phone number
    $startDate, // date range
    $endDate,   // date range
    $type,      // message type
    $ndi,       // NDI
    $getSmsId   // gets messages with custom message ID
);
```

#### Receiving messages delivery reports

```
$smsService = $this->get('net_labs_sms');

$reports = $smsService->getReports(
    $number,    // phone number
    $startDate, // date range
    $endDate,   // date range
    $smsId,     // smsId from serwersms.pl
    $usmsid     // custom message ID
);
```
