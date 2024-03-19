<?php

namespace MsPedidosApp\adapters\queue\consumer;

use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\queue\base\KafkaConfig;
use MsPedidosApp\adapters\queue\QueueGateway;
use MsPedidosApp\core\exceptions\ServicesException;
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
     * @throws GuzzleException
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws ServicesException
     */
    public function listen():array
    {
        foreach($this->topics as $topic){
            $newTopic = $this->consumer->newTopic($topic);
            $newTopic->consumeQueueStart(0, RD_KAFKA_OFFSET_BEGINNING, $this->queue);
            $newTopic->consumeQueueStart(1, RD_KAFKA_OFFSET_BEGINNING, $this->queue);
        }

        while (true) {
            $msg = $this->queue->consume($this->timeout);
            if($msg->err){
                throw new Exception('Erro de leitura na fila do Kafka');
            }

            QueueGateway::interrupt(['topic' => $msg->topic_name, 'payload' => $msg->payload]);
        }
    }

}
