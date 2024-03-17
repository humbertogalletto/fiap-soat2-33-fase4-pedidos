<?php

namespace MsPedidosApp\adapters\queue\base;

use RdKafka\Conf;

class KafkaConfig
{
    protected $endpoint;
    protected $conf;

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
        $this->endpoint = $endpoint;
        $this->conf = new Conf();
        $this->conf->set('metadata.broker.list', $endpoint);

    }
}
