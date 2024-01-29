<?php

namespace MsPedidosApp\adapters\ms\base;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SendOut
{

    /**
     * @throws GuzzleException
     */
    public function post($endpoint, $data)
    {
        $client = new Client([
            'base_uri' => $endpoint,
            'timeout' => 2.0,
        ]);

        $r = $client->request('POST', $endpoint, ['body' => $data]);

        return $r->getBody()->getContents();
    }

}
