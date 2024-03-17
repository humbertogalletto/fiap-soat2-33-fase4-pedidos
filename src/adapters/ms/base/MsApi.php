<?php

namespace MsPedidosApp\adapters\ms\base;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MsPedidosApp\adapters\queue\QueueGateway;

class MsApi
{

    protected string $endpoint;

    public function getEndpoint() :string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @throws GuzzleException
     */
    public function post(array $data): string
    {
        $client = new Client([
            'base_uri' => $this->getEndpoint(),
            'timeout' => 2.0,
        ]);

        $r = $client->request('POST', $this->getEndpoint(), ['body' => $data]);

        return $r->getBody()->getContents();
    }

    /**
     * @param string $topic
     * @param string $message
     * @return string
     * @throws \RdKafka\Exception
     */
    public function sendToQueue(string $message, string $topic):string
    {
        return QueueGateway::send($message, $topic);
    }

}
