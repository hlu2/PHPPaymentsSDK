<?php

namespace QuickBooksOnline\Payments\Facade;

use QuickBooksOnline\Payments\Module\BankAccount;
use QuickBooksOnline\Payments\Module\Token;
use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\HttpClients\Request\RequestFactory;
use QuickBooksOnline\Payments\HttpClients\Request\RequestType;

class BankAccountBuilder
{
    /**
     * Builder should not contain the endpoint name. Should only have bodies.
     */
    public static function buildFrom(array $array)
    {
        return new BankAccount($array);
    }

    /**
     * Create a Bank Account
     */
    public static function createBankAccount(BankAccount $bankaccount, $customerID, string $requestId, $context) : RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::BANKACCOUNT);
        $request->setMethod(RequestInterface::POST)
            ->setUrl($context->getBaseUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/bank-accounts")
            ->setHeader($context->getStandardHeaderWithRequestID($requestId))
            ->setBody(FacadeConverter::getJsonFrom($bankaccount));
        return $request;
    }

    /**
     * Create a Bank Account from Token
     */
    public static function createBankAccountFromToken($customerID, string $tokenValue, string $requestId, $context) : RequestInterface
    {
        $token = FacadeConverter::createTokenObjFromValue($tokenValue);
        $request = RequestFactory::createStandardIntuitRequest(RequestType::BANKACCOUNT);
        $request->setMethod(RequestInterface::POST)
            ->setUrl($context->getBaseUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/bank-accounts" . "/createFromToken")
            ->setHeader($context->getStandardHeaderWithRequestID($requestId))
            ->setBody(FacadeConverter::getJsonFrom($token));
        return $request;
    }

    /**
     * Delete a Customer's bankAccount
     */
    public static function deleteBankAccountFor($customerID, string $bankAccountId, string $requestId, $context): RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::BANKACCOUNT);
        $request->setMethod(RequestInterface::DELETE)
              ->setUrl($context->getBaseUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/bank-accounts" . "/" .$bankAccountId)
              ->setHeader($context->getStandardHeaderWithRequestIDForDelete($requestId));
        return $request;
    }

    /**
     * Get all banks for a Customer
     */
    public static function getAllbankAccountsFor($customerID, string $requestId, $context): RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::BANKACCOUNT);
        $request->setMethod(RequestInterface::GET)
              ->setUrl($context->getBaseUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/bank-accounts")
              ->setHeader($context->getStandardHeaderWithRequestID($requestId));
        return $request;
    }

    /**
     * Get a Bank Account
     */
    public static function getAbankAccountFor($customerID, string $bankAccountId, string $requestId, $context): RequestInterface
    {
        $request = RequestFactory::createStandardIntuitRequest(RequestType::BANKACCOUNT);
        $request->setMethod(RequestInterface::GET)
              ->setUrl($context->getBaseUrl() . EndpointUrls::CUSTOMER_URL . "/" . $customerID . "/bank-accounts" . "/" .$bankAccountId)
              ->setHeader($context->getStandardHeaderWithRequestID($requestId));
        return $request;
    }
}
