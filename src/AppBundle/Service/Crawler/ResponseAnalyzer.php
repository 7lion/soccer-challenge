<?php

namespace AppBundle\Service\Crawler;

class ResponseAnalyzer
{
    public function isLastPage(array $response)
    {
        if (isset($response['commands'][1]['parameters']['attributes']['has_previous_page'])) {
            return !(bool) $response['commands'][1]['parameters']['attributes']['has_previous_page'];
        }

        return null;
    }

    public function getHtml(array $response)
    {
        if (isset($response['commands'][0]['parameters']['content'])) {
            return $response['commands'][0]['parameters']['content'];
        }

        return null;
    }

    public function parseHtmlResponse($html)
    {
        return json_decode($html, true);
    }
}
