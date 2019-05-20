<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\{Card, Token};
use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\HttpClients\Request\RequestFactory;
use QuickBooksOnline\Payments\HttpClients\Request\RequestType;

class CardBuilder
{
  /**
   * Builder should not contain the endpoint name. Should only have bodies.
   */
  public static function buildFrom(array $array)
  {
      return new Card($array);
  }

  /**
   * Create a Card
   */
  public static function createCard(Card $card, $customerID, string $requestId, $client) : RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::POST)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards")
            ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
            ->setBody(FacadeConverter::getJsonFrom($card));
      return $request;
  }

  /**
   * Delete a Customer's Card
   */
  public static function deleteCard($customerID, string $cardId, string $requestId, $client): RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::DELETE)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards" . "/" .$cardId)
            ->setHeader($client->getContext()->getStandardHeaderWithRequestIDForDelete($requestId));
      return $request;
  }

  /**
   * Get all cards associated with a Customer
   */
  public static function getAllCards($customerID, string $requestId, $client): RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::GET)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards")
            ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId));
      return $request;
  }

  /**
   * Create a Card from Token
   */
  public static function createCardFromToken($customerID, string $tokenValue, $requestId, $client): RequestInterface
  {
      $token = CardBuilder::createTokenObjFromValue($tokenValue);
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::POST)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards" . "/createFromToken")
            ->setHeader($client->getContext()->getStandardHeaderWithRequestID($requestId))
            ->setBody(FacadeConverter::getJsonFrom($token));
      return $request;
  }

  public static function createTokenObjFromValue($val) : Token {
      $token = new Token();
      $token->value = $val;
      return $token;
  }


}
