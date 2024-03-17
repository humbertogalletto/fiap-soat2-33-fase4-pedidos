<?php

namespace MsPedidosApp\adapters\queue\producer;

use MsPedidosApp\adapters\queue\base\KafkaConfig;
use RdKafka\Exception;
use RdKafka\Producer;
use RdKafka\ProducerTopic;

class KafkaProducer extends KafkaConfig
{

    protected ProducerTopic $topic;
    protected string $message;
    protected Producer $producer;

    /**
     * @param string $endpoint
     * @param string $topic
     * @param string $message
     */
    public function __construct(string $broker, string $topic, string $message)
    {
        $this->message = $message;
        $this->setConf($broker);
        $this->producer = new Producer($this->getConf($broker));
        $this->producer->addBrokers($broker);
        $this->topic = $this->producer->newTopic($topic);
    }

    /**
     * @return true
     * @throws Exception
     */
    public function send()
    {
        $this->topic->produce(RD_KAFKA_PARTITION_UA , 0, $this->message);
        return true;
    }


}
