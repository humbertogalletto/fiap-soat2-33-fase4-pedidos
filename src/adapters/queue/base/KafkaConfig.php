<?php

namespace MsPedidosApp\adapters\queue\base;

use RdKafka\Conf;

class KafkaConfig
{
    protected $broker;
    protected $conf;

    protected $timeout = 100;

    /**
     * @param $config
     */
    public function getConf(string $endpoint): Conf
    {
        $this->setConf($endpoint);
        return $this->conf;

    }

    public function setConf(string $endpoint): void
    {
        $this->broker = $endpoint;
        $this->conf = new Conf();
        $this->conf->set('metadata.broker.list', $endpoint);
        $this->conf->set('message.max.bytes', 1000000);

    }
}
