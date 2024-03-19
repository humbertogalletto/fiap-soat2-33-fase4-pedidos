<?php

namespace MsPedidosApp\adapters\queue;


use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\db\DBGateway;
use MsPedidosApp\adapters\queue\consumer\KafkaConsumer;
use MsPedidosApp\adapters\queue\producer\KafkaProducer;
use MsPedidosApp\controllers\PedidoController;
use MsPedidosApp\core\exceptions\ServicesException;
use MsPedidosApp\core\services\PagamentoService;
use MsPedidosApp\core\services\PedidoService;
use MsPedidosApp\core\types\EnumStatus;
use RdKafka\Conf;
use RdKafka\Exception;
use RdKafka\Producer;

class QueueGateway
{
    private static ?self $instance = null;

    /**
     * Factoring
     */
    private function __construct(){}

    /**
     * @param string $message
     * @param string $topic
     * @return bool
     * @throws Exception
     */
    public static function send(string $message, string $topic): bool
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $queue = self::$instance;

        return $queue->sendToKafka($message, $topic);
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws ServicesException|Exception
     */
    public static function listen():void
    {
        if (is_null(self::$instance)){
            self::$instance = new static();
        }
        $queue = self::$instance;

        $queue->listenToKafka();
    }

    /**
     * @param string $message
     * @param string $topic
     * @return bool
     * @throws Exception
     */
    private function sendToKafka(string $message, string $topic): bool
    {
        $broker = "{$_ENV['KAFKA_BROKER']}:{$_ENV['KAFKA_PORT']}";
        $kafkaProducer = new KafkaProducer($broker, $topic, $message);
        return $kafkaProducer->send();
    }

    /**
     * @return array
     * @throws Exception
     */
    private function listenToKafka():void
    {
        $topics = ["{$_ENV['KAFKA_TOPIC_PAGAMENTO_APROVADO']}", "{$_ENV['KAFKA_TOPIC_PAGAMENTO_NAO_APROVADO']}"];
        $broker = "{$_ENV['KAFKA_BROKER']}:{$_ENV['KAFKA_PORT']}";
        $kafkaConsumer = new KafkaConsumer($broker, $topics);
        $kafkaConsumer->listen();
    }

    /**
     * @param $msg
     * @return void
     * @throws GuzzleException
     * @throws ServicesException
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public static function interrupt($msg):void
    {
        switch($msg['topic']){
            case "{$_ENV['KAFKA_TOPIC_PAGAMENTO_APROVADO']}":
                (new PagamentoService())->process(json_decode($msg['payload']));
                break;
            case "{$_ENV['KAFKA_TOPIC_PAGAMENTO_NAO_APROVADO']}":
                $data = json_decode($msg['payload'], true);
                (new PedidoService(DBGateway::getRepository()))
                    ->update(
                        $data['externalId'],
                        ['status' => EnumStatus::FINALIZADO]
                    );
                break;
            default:
                break;
        }
    }
}
