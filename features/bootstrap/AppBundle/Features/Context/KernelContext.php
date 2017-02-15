<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Prophecy\Argument;
use GuzzleHttp\Psr7;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit_Framework_Assert as Assertions;

class KernelContext implements Context
{
    use KernelDictionary;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Client
     */
    private $client;

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $container = $this->getContainer();
        $this->client = $container->get('test.client');
        $this->client->followRedirects(false);
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with query parameters:$/
     */
    public function iSendARequestWithQueryParameters($method, $url, TableNode $table)
    {
        $parameters = [];
        foreach ($table->getColumnsHash() as $row) {
            $parameters[$row['parameter']] = $row['value'];
        }

        $this->client->request($method, $url, $parameters);
        $this->response = $this->client->getResponse();
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $this->client->request($method, $url);
        $this->response = $this->client->getResponse();
    }

    /**
     * Prints last response body.
     *
     * @Then print response
     */
    public function printResponse()
    {
        $request = $this->client->getRequest();
        $response = $this->response;

        $contentType = $this->response->headers->get('Content-Type');

        if (false !== strpos($contentType, 'json')) {
            echo sprintf(
                "%s %s => %d:\n%s",
                $request->getMethod(),
                $request->getUri(),
                $response->getStatusCode(),
                json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT)
            );
        } else {
            echo sprintf(
                "%s %s => %d:\n%s",
                $request->getMethod(),
                $request->getUri(),
                $response->getStatusCode(),
                $response->getContent()
            );
        }
    }

    /**
     * Checks that response body contains JSON from PyString.
     *
     * Do not check that the response body /only/ contains the JSON from PyString,
     *
     * @param PyStringNode $jsonString
     *
     * @throws \RuntimeException
     *
     * @Then /^(?:the )?response should contain json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString)
    {
        $etalon = json_decode($jsonString->getRaw(), true);
        $actual = json_decode($this->response->getContent(), true);
        if (null === $etalon) {
            throw new \RuntimeException(
                "Can not convert etalon to json:\n" . $jsonString->getRaw()
            );
        }
        Assertions::assertGreaterThanOrEqual(
            count($etalon),
            count($actual)
        );
        foreach ($etalon as $key => $needle) {
            Assertions::assertArrayHasKey($key, $actual, sprintf('The response doesn\'t have the key "%s"', $key));
            Assertions::assertSame(
                $etalon[$key],
                $actual[$key],
                sprintf(
                    'The value differs from expected in the key "%s"',
                    $key
                )
            );
        }
    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then /^(?:the )?response code should be (\d+)$/
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->response->getStatusCode());

        Assertions::assertSame($expected, $actual);
    }
}