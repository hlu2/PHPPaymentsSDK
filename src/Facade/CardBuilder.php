<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\Card;
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
  public static function createCard(Card $card, string $customerID, string $requestId, $client) : RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::POST)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards")
            ->setHeader($client->getStandardHeaderWithRequestID($requestId))
            ->setBody(FacadeConverter::getJsonFrom($card));
      return $request;
  }

  /**
   * Delete a Customer's Card
   */
  public static function deleteCard(string $customerID, string $cardId, $requestId, $client): RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::DELETE)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards" . "/" .$cardId)
            ->setHeader($client->getStandardHeaderWithRequestIDForDelete($requestId));
      return $request;
  }

  /**
   * Get all cards associated with a Customer
   */
  public static function getAllCards(string $customerID, $requestId, $client): RequestInterface
  {
      $request = RequestFactory::createStandardIntuitRequest(RequestType::CARD);
      $request->setMethod(RequestInterface::GET)
            ->setUrl($client->getUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/cards")
            ->setHeader($client->getStandardHeaderWithRequestID($requestId));
      return $request;
  }


}
