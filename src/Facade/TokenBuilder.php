<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\HttpClients\Request\RequestFactory;
use QuickBooksOnline\Payments\HttpClients\Request\RequestType;
use QuickBooksOnline\Payments\Module\{Card, BankAccount, Token};


class TokenBuilder
{

    /**
     * Create a Token
     * @param mixed bankAccount or Card to exchange for token.
     */
    public static function createToken($tokenBody, bool $isIE, $requestId, $context): RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::TOKEN);
        $requestBody = array();
        if($tokenBody instanceof Card){
            $requestBody['card'] = $tokenBody;
        }else if($tokenBody instanceof BankAccount){
            $requestBody['bankAccount'] = $tokenBody;
        }
        $url = $context->getBaseUrl() . ($isIE ? EndpointUrls::TOKEN_URL_IE : EndpointUrls::TOKEN_URL);
        $request->setMethod(RequestInterface::POST)
              ->setUrl($url)
              ->setHeader($context->getNonAuthHeaderWithRequestID($requestId))
              ->setBody(FacadeConverter::getJsonFrom($requestBody));
        return $request;
    }
}
