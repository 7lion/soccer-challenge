<?php

namespace AppBundle\Service\Client;

use GuzzleHttp\Client;

class GuzzleClient implements ClientInterface
{
    public function request($method, $uri)
    {
        $client = new Client();

        return $client->request($method, $uri);
    }
}
