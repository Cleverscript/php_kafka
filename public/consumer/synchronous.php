<?php
//Composer autoload
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

$config = new ConsumerConfig();
$config->setBroker('kafka:29092'); //127.0.0.1
$config->setTopic('test'); // topic
$config->setGroupId('testGroup'); // group ID
$config->setClientId('test_custom'); // client ID. Use different settings for different consumers.
$config->setGroupInstanceId('test_custom'); // group instance ID. Use different settings for different consumers.
$consumer = new Consumer($config);
while(true) {
    $message = $consumer->consume();
    if($message) {
        var_dump($message->getKey() . ':' . $message->getValue());
        $consumer->ack($message); // commit manually
    }
    sleep(1);
}