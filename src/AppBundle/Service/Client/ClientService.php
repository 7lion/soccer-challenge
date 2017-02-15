<?php

namespace AppBundle\Service\Client;

class ClientService
{
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getContentByUrl($url)
    {
        $response = $this->client->request('GET', $url);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException(
                sprintf('Status code is invalid. Given %d', $response->getStatusCode())
            );
        }

        $content = $response->getBody()->getContents();
        if (!$content) {
            throw new \RuntimeException('Content is empty');
        }

        return $content;
    }
}
