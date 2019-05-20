<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\Charge;
use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\HttpClients\Request\RequestFactory;
use QuickBooksOnline\Payments\HttpClients\Request\RequestType;

class ChargeBuilder
{
    public static function buildFrom(array $array)
    {
        return new Charge($array);
    }

    /**
     * Create a Charge
     */
    public static function createChargeRequest(Charge $charge, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::CHARGE);
        $request->setMethod(RequestInterface::POST)
              ->setUrl($client->getUrl() . EndpointUrls::CHARGE_URL)
              ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
              ->setBody(FacadeConverter::getJsonFrom($charge));
        return $request;
    }

    /**
     * Retrieve a Charge by Id
     */
    public static function createGetChargeRequest(string $chargeId, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::CHARGE);
        $request->setMethod(RequestInterface::GET)
              ->setUrl($client->getUrl() . EndpointUrls::CHARGE_URL . "/" . $chargeId)
              ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId));
        return $request;
    }

    /**
     * Capture a Charge by Id
     */
    public static function createCaptureChargeRequest(Charge $charge, string $chargeId, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::CHARGE);
        $request->setMethod(RequestInterface::POST)
                  ->setUrl($client->getUrl() . EndpointUrls::CHARGE_URL . "/" . $chargeId . "/capture")
                  ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
                  ->setBody(FacadeConverter::getJsonFrom($charge));
        return $request;
    }

    /**
      * Refund a Charge
      */
    public static function createRefundChargeRequest(Charge $charge, string $chargeId, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::CHARGE);
        $request->setMethod(RequestInterface::POST)
                  ->setUrl($client->getUrl() . EndpointUrls::CHARGE_URL . "/" . $chargeId . "/refunds")
                  ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
                  ->setBody(FacadeConverter::getJsonFrom($charge));
        return $request;
    }

    /**
     * Get a Refund By ID
     */
    public static function refundBy(string $chargeId, string $refundId, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::CHARGE);
        $request->setMethod(RequestInterface::GET)
                ->setUrl($client->getUrl() . EndpointUrls::CHARGE_URL . "/" . $chargeId . "/refunds" . "/" . $refundId)
                ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId));
        return $request;
    }
}
