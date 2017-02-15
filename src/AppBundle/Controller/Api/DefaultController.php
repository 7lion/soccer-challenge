<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;

class DefaultController extends FOSRestController
{
    /**
     * @Annotations\QueryParam(
     *     name="from",
     *     requirements="^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$",
     *     nullable=true,
     *     strict=true
     * )
     * @Annotations\QueryParam(
     *     name="to",
     *     requirements="^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$",
     *     nullable=true,
     *     strict=true
     * )
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getStandingsAction(ParamFetcherInterface $paramFetcher)
    {
        if ($dateFrom = $paramFetcher->get('from')) {
            $dateFrom = new \DateTime($dateFrom);
            $dateFrom->setTime(00, 00, 00);
        }
        if ($dateTo = $paramFetcher->get('to')) {
            $dateTo = new \DateTime($dateTo);
            $dateTo->setTime(23, 59, 59);
        }

        $data = $this->get('match.service')->getStandings($dateFrom, $dateTo);

        return $this->view($data);
    }
}
