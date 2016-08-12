# ORTC-PHP (PHP API Client for Realtime.co)

[![Build Status](https://travis-ci.org/nikapps/ortc-php.svg?branch=master)](https://travis-ci.org/nikapps/ortc-php)

![Real-time Framework - ORTC](https://www.dropbox.com/s/z6by8jind9s3m5v/realtime.png?raw=1)

It's an unofficial client for [ORTC](http://framework.realtime.co/messaging) (Open Real-Time Connectivity, realtime & cloud-based pub/sub framework from [realtime.co](http://www.realtime.co) for PHP 5.4+), but yet powerful, composer based and compatible with psr-1, psr-2 and psr-4.


## Installation
Using composer, install this [package](https://packagist.org/packages/nikapps/ortc-php) by running this command:

```
composer require nikapps/ortc-php
```


## Configuration

#### Get Application Key & Private Key
First of all, you should register on realtime.co and get your api keys.

* Login/Register at https://accounts.realtime.co

* Create new Subscription 

* You can see your `Application Key` and `Private Key`

* If you want to use authentication, you should enable it in your panel.

#### Ortc Config
Before you can call any api call, you should set your credentials.

~~~php
$ortcConfig = new \Nikapps\OrtcPhp\Configs\OrtcConfig();

$ortcConfig->setApplicationKey('YOUR_APPLICATION_KEY'); //you application key
$ortcConfig->setPrivateKey('YOUR_PRIVATE_KEY'); //Your private key
$ortcConfig->setVerifySsl(true); //verify ssl/tls certificate
~~~

#### Done!

## Usage

#### Get Balancer URL (Manually)

This package automatically get balancer url (best available server), but if you want fetch a new balancer url manually:

~~~php
$ortc = new \Nikapps\OrtcPhp\Ortc($ortcConfig);

$balancerUrl = $ortc->getBalancerUrl();

echo 'Balancer Url: ' . $balancerUrl->getUrl();
~~~

#### Authentication
In order to authenticate a user:

* Create channel(s):

First, you should create your channels:

~~~php
$channelOne = new \Nikapps\OrtcPhp\Models\Channel();
$channelOne->setName('CHANNEL_ONE_NAME');
$ChannelOne->setPermission(Channel::PERMISSION_WRITE);

$channelTwo = new \Nikapps\OrtcPhp\Models\Channel();
$channelTwo->setName('CHANNEL_TWO_NAME');
$channelTwo->setPermission(Channel::PERMISSION_READ);

$channels = [
	$channelOne,
	$channelTwo
];
~~~

* Authenticate:

Then authenticate the user:

~~~php

$authToken = 'YOUR_AUTH_TOKEN'; //your authentication token

$authRequest = new \Nikapps\OrtcPhp\Models\Requests\AuthRequest();
$authRequest->setAuthToken($authToken);
$authRequest->setExpireTime(5 * 60); //token ttl (expiration time) in seconds
$authRequest->setPrivate(true); //Indicates whether the authentication token is private
$authRequest->setChannels($channels);

$ortc = new \Nikapps\OrtcPhp\Ortc($ortcConfig);
$ortc->authenticate($authRequest);
~~~

#### Send Message (Push)
In order to push a message to a channel:

~~~php
$authToken = 'YOUR_AUTH_TOKEN'; //your authentication token

$sendMessageRequest = new \Nikapps\OrtcPhp\Models\Requests\SendMessageRequest();
$sendMessageRequest->setAuthToken($authToken);
$sendMessageRequest->setChannelName('CHANNEL_NAME');
$sendMessageRequest->setMessage('YOUR_MESSAGE');

$ortc = new \Nikapps\OrtcPhp\Ortc($ortcConfig);
$ortc->sendMessage($sendMessageRequest);
~~~

*If you using UTF-8 messages, it's better to use `base64_encode()`.*

## Exceptions
* **OrtcException**

*Parent of other exceptions*

* **UnauthorizedException**

*When token is expired or credentials are invalid*

* **InvalidBalancerUrlException**

*When balancer url is invalid*

-You can get url by `getUrl()`

* **BatchRequestException**

*When at least one message response is failed*

-You can get all results by `getResults()`. It returns a `\GuzzleHttp\BatchResults` object.

* **NetworkErrorException**

*Guzzle ClientExcpetion*

-You can get guzzle exception by `getClientException()`


## Dependencies

* [GuzzleHttp ~5.2](https://packagist.org/packages/guzzlehttp/guzzle)
* [Uuid ^3.4](https://packagist.org/packages/ramsey/uuid)


## Ortc Documentation
This package is based on ORTC REST API. You can download REST service documentation from this url:

```
http://messaging-public.realtime.co/documentation/rest/2.1.0/RestServices.pdf
```

Also, you can download official ORTC library for PHP from this url:

```
http://messaging-public.realtime.co/api/download/php/2.1.0/ApiPhp.zip
```
## Framework Integrations

* **Laravel 4/5:** [nikapps/ortc-laravel](https://github.com/nikapps/ortc-laravel)

## TODO

* ~~add UnitTest (codeception or phpunit)~~ (Thanks to [@moura137](https://github.com/moura137))
* subscribe to channel(s) by Ratchet/Nodejs/Icicle/Amphp
* support mobile push notification (ios & android)
* support presence channels
* Anything else?!

## Contribute

Wanna contribute? simply fork this project and make a pull request!


## License
This project released under the [MIT License](http://opensource.org/licenses/mit-license.php).

```
/*
 * Copyright (C) 2015 NikApps Team.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * 1- The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * 2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
```

## Donation

[![Donate via Paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G3WRCRDXJD6A8)
