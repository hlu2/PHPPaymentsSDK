<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\ECheck;
use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\HttpClients\Request\RequestFactory;
use QuickBooksOnline\Payments\HttpClients\Request\RequestType;

class ECheckBuilder
{
    public static function buildFrom(array $array)
    {
        return new ECheck($array);
    }

    /**
     * Create a debit
     */
    public static function debit(ECheck $debitBody, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::ECHECK);
        $request->setMethod(RequestInterface::POST)
              ->setUrl($client->getUrl() . EndpointUrls::ECHECK_URL)
              ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
              ->setBody(FacadeConverter::getJsonFrom($debitBody));
        return $request;
    }


    /**
     * Retrieve an echeck
     */
    public static function retrieveECheck(string $echeckId, string $requestId, $client) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::ECHECK);
        $request->setMethod(RequestInterface::GET)
                  ->setUrl($client->getUrl() . EndpointUrls::ECHECK_URL . "/" . $echeckId)
                  ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId));
        return $request;
    }
}
