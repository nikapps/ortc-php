<?php

use Nikapps\OrtcPhp\Configs\OrtcConfig;
use Nikapps\OrtcPhp\Models\Channel;
use Nikapps\OrtcPhp\Models\Requests\AuthRequest;
use Nikapps\OrtcPhp\Models\Requests\SendMessageRequest;
use Nikapps\OrtcPhp\Ortc;

require_once '../vendor/autoload.php';

$ortcConfig = new OrtcConfig();

$ortcConfig->setApplicationKey('YOUR_APPLICATION_KEY');
$ortcConfig->setPrivateKey('YOUR_PRIVATE_KEY');
$ortcConfig->setVerifySsl(false);

$authToken = 'YOUR_AUTHENTICATION_TOKEN';

$channels = [];
$testChannel = new Channel();
$testChannel->setName('CHANNEL_NAME');
$testChannel->setPermission(Channel::PERMISSION_WRITE);

$channels[] = $testChannel;

$ortc = new Ortc($ortcConfig);

if (isset($_POST['message'])) {
    $sendMessageRequest = new SendMessageRequest();
    $sendMessageRequest->setAuthToken($authToken);
    $sendMessageRequest->setChannelName($testChannel->getName());
    $sendMessageRequest->setMessage($_POST['message']);

    $ortc->sendMessage($sendMessageRequest);
} else {
    $authRequest = new AuthRequest();
    $authRequest->setAuthToken($authToken);
    $authRequest->setExpireTime(5 * 60);
    $authRequest->setPrivate(true);
    $authRequest->setChannels($channels);

    $authResponse = $ortc->authenticate($authRequest);
}

?>
<!doctype html>
<html>
<head>
    <title>Send Message</title>
</head>
<body>
<form action="send.php" method="post">
    <label for="message">Message: </label><br/>
    <textarea id="message" name="message"></textarea>
    <br/>
    <input type="submit" value="Send to channel"/>
</form>

</body>
</html>