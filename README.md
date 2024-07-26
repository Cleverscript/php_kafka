# php_kafka - кластер Kafka из трех брокеров

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

Используется пакет для работы с Kafka из PHP [phpkafka](https://github.com/swoole/phpkafka)