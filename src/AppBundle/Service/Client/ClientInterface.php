<?php

namespace AppBundle\Service\Client;

use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{
    /**
     * @param string $method
     * @param string $uri
     *
     * @return ResponseInterface
     *
     * @throws \RuntimeException
     */
    public function request($method, $uri);
}
