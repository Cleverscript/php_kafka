# php_kafka - кластер Kafka из трех брокеров

![Иллюстрация к проекту](https://github.com/Cleverscript/php_kafka/raw/main/kafka_brockers.png)

---

Используем скрипты работы с контейнерами, перходим в директорию
```bash
cd ./docker/tools/
```

И далее в ней

запуск Kafka кластера из трех брокеров
```bash
./restart.sh
```

остановка контейнеров
```bash
./docker/tools/stop.sh
```

логи контейнеров
```bash
./docker/tools/logs.sh
```

зайти в контейнер с PHP
```bash
./docker/tools/exec.sh
```

---

### Как протестировать отправку сообщения producer`ом в Rafka и его прочтение consumer`ом 

В браузере запускаем скрипт http://localhost:8080/producer/ который создает топик (тему), и отправляет в нее 2 сообщение с радномными ключами и значениями, при чем для второго сообщения еще и задаются заголовки
```php
//Composer autoload
$_SERVER['DOCUMENT_ROOT'] = '../';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use longlang\phpkafka\Producer\Producer;
use longlang\phpkafka\Producer\ProducerConfig;
use longlang\phpkafka\Protocol\RecordBatch\RecordHeader;

$config = new ProducerConfig();
$config->setBootstrapServer('kafka:29092'); //127.0.0.1
$config->setUpdateBrokers(true);
$config->setAcks(-1);
$producer = new Producer($config);
$topic = 'test';
$value = (string) microtime(true);
$key = uniqid('', true);
$producer->send('test', $value, $key);

// set headers
// key-value or use RecordHeader
$headers = [
    'key1' => 'value1',
    (new RecordHeader())->setHeaderKey('key2')->setValue('value2'),
];
$producer->send('test', $value, $key, $headers);
```

Увидеть сообщения в теме 'test' можно зайдя в веб интерфейс kafka-ui доступный по адресу
http://localhost:8090/ui/clusters/local/all-topics/test/messages
![Иллюстрация к проекту](https://github.com/Cleverscript/php_kafka/raw/main/kafka_ui_messages.png)


Прочесть сообщения из Kafka можно зайдя в контейнер PHP (см. пример выше) и запустив любой из вариантов consumer:

асинхронный 
```php
//Composer autoload
$_SERVER['DOCUMENT_ROOT'] = '../';
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
```
```bash
php -f asynchronous_cli.php 
```
![Иллюстрация к проекту](https://github.com/Cleverscript/php_kafka/raw/main/asynchronous_cli.png)

или синхронный:
```php
//Composer autoload
$_SERVER['DOCUMENT_ROOT'] = '../';
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
```
```bash
php -f synchronous_cli.php
```
![Иллюстрация к проекту](https://github.com/Cleverscript/php_kafka/raw/main/synchronous_cli.png)

---

Используется пакет для работы с Kafka из PHP [phpkafka](https://github.com/swoole/phpkafka)