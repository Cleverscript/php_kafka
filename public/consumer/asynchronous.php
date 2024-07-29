<?php
//Composer autoload
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;

function consume(ConsumeMessage $message)
{
    var_dump($message->getKey() . ':' . $message->getValue());
    // $consumer->ack($message); // If autoCommit is set as false, commit manually.
}
$config = new ConsumerConfig();
$config->setBroker('kafka:29092'); //127.0.0.1
$config->setTopic('test'); // topic
$config->setGroupId('testGroup'); // group ID
$config->setClientId('test'); // client ID. Use different settings for different consumers.
$config->setGroupInstanceId('test'); // group instance ID. Use different settings for different consumers.
$config->setInterval(0.1);
$consumer = new Consumer($config, 'consume');
$consumer->start();