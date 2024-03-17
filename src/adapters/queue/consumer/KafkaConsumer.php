<?php

namespace MsPedidosApp\adapters\queue\consumer;

use MsPedidosApp\adapters\queue\base\KafkaConfig;
use RdKafka\Consumer;
use RdKafka\ConsumerTopic;
use RdKafka\Exception;
use RdKafka\Message;
use RdKafka\Queue;

class KafkaConsumer extends KafkaConfig
{
    protected array $topics;

    protected Consumer $consumer;
    protected Queue $queue;

    /**
     * @param string $broker
     * @param array $topics
     */
    public function __construct(string $broker, array $topics)
    {
        $this->setConf($broker);
        $this->consumer = new Consumer($this->getConf($broker));
        $this->consumer->addBrokers($broker);
        $this->queue = $this->consumer->newQueue();
        $this->topics = $topics;

    }

    /**
     * @return array
     * @throws Exception
     */
    public function listen():array
    {
        foreach($this->topics as $topic){
            $newTopic = $this->consumer->newTopic($topic);
            $newTopic->consumeQueueStart(0, RD_KAFKA_OFFSET_BEGINNING, $this->queue);
            $newTopic->consumeQueueStart(1, RD_KAFKA_OFFSET_BEGINNING, $this->queue);
        }

        while (true) {
            $msg = $this->queue->consume(0, 1000);
            if($msg->err){
                throw new Exception('Erro de leitura na fila do Kafka');
            }

            return ['topic' => $msg->topic_name, 'payload' => $msg->payload];
        }
    }

}
