<?php
namespace QuickBooksOnline\Payments;

use QuickBooksOnline\Payments\Facade\FacadeConverter;
use QuickBooksOnline\Payments\Facade\ChargeBuilder;
use QuickBooksOnline\Payments\Facade\CardBuilder;
use QuickBooksOnline\Payments\Facade\TokenBuilder;
use QuickBooksOnline\Payments\Facade\ECheckBuilder;
use QuickBooksOnline\Payments\Facade\BankAccountBuilder;
use QuickBooksOnline\Payments\HttpClients\core\ClientFactory;
use QuickBooksOnline\Payments\HttpClients\Response\IntuitResponse;
use QuickBooksOnline\Payments\HttpClients\Response\ResponseInterface;
use QuickBooksOnline\Payments\HttpClients\Response\ResponseFactory;
use QuickBooksOnline\Payments\HttpClients\Request\IntuitRequest;
use QuickBooksOnline\Payments\HttpClients\Request\RequestInterface;
use QuickBooksOnline\Payments\Module\BankAccount;
use QuickBooksOnline\Payments\Module\Charge;
use QuickBooksOnline\Payments\Module\Card;
use QuickBooksOnline\Payments\Module\ECheck;
use QuickBooksOnline\Payments\Module\Token;

class PaymentClient
{

    /**
     * The Http context for the client.
     */
    private $context;

    /**
     * A list of interceptors to be used in the client
     */
    private $interceptors = array();


    private $oauth2Authenticator;


    private $httpClient;


    public function __construct()
    {
        $this->httpClient = ClientFactory::buildCurlClient();
        $this->context = new ClientContext();
    }

    /**
     * A generic function to send any request that implements RequestInterface
     */
    public function send(RequestInterface $request) : ResponseInterface{
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Create a Charge
     */
    public function charge(Charge $charge, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ChargeBuilder::createChargeRequest($charge, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Retrieve a Charge
     */
    public function retrieveCharge(string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ChargeBuilder::createGetChargeRequest($chargeId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Capture a Charge
     */
    public function captureCharge(Charge $charge, string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ChargeBuilder::createCaptureChargeRequest($charge, $chargeId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Refund a Charge
     */
    public function refundCharge(Charge $charge, string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ChargeBuilder::createRefundChargeRequest($charge, $chargeId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Get a refund by ID.
     */
    public function getRefundDetail(string $chargeId, string $refundId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ChargeBuilder::refundBy($chargeId, $refundId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a Card
    */
    public function createCard(Card $card, $customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = CardBuilder::createCard($card, $customerID, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Delete a Card
    */
    public function deleteCard($customerID, string $cardId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = CardBuilder::deleteCard($customerID, $cardId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Get all cards
    */
    public function getAllCardsFor($customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = CardBuilder::getAllCards($customerID, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }


    /**
    * Create a token
    */
    public function createCardFromToken($customerID, string $tokenValue, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = CardBuilder::createCardFromToken($customerID, $tokenValue, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a token
    */
    public function createToken($body, bool $isIE = false, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = TokenBuilder::createToken($body, $isIE, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a debit
    */
    public function debit(ECheck $debitBody, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ECheckBuilder::debit($debitBody, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Retrieve an Echeck
    */
    public function retrieveECheck(string $echeckId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ECheckBuilder::retrieveECheck($echeckId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Void or Refund an Echeck
    */
    public function voidOrRefundEcheck(ECheck $echeck, string $echeckId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ECheckBuilder::voidOrRefundEcheck($echeck, $echeckId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Retrieve a refund
    */
    public function retrieveRefund(string $echeckId, string $refundId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = ECheckBuilder::retrieveRefund($echeckId, $refundId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a bank account
    */
    public function createBankAccount(BankAccount $account, $customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = BankAccountBuilder::createBankAccount($account, $customerID, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a bank account from token
    */
    public function createBankAccountFromToken($customerID, string $tokenValue, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = BankAccountBuilder::createBankAccountFromToken($customerID, $tokenValue, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Delete a bank account
    */
    public function deleteBankAccount($customerID, string $bankAccountId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = BankAccountBuilder::deleteBankAccountFor($customerID, $bankAccountId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Get all bank account
    */
    public function getAllBankAccount($customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = BankAccountBuilder::getAllbankAccountsFor($customerID, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }


    /**
    * Get all bank account
    */
    public function getABankAccount($customerID, string $bankAccountId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = ClientContext::generateRequestID();
        }
        $request = BankAccountBuilder::getAbankAccountFor($customerID, $bankAccountId, $requestId, $this->getContext());
        $response = $this->httpClient->send($request);
        FacadeConverter::updateResponseBodyToObj($response);
        return $response;
    }



    public function getUrl()
    {
        return $this->context->getBaseUrl();
    }

    /**
    * Set the URL for the API. It is either https://sandbox.api.intuit.com or
    *  "https://api.intuit.com"
    */
    public function setUrl(string $url)
    {
        if (!isset($url) || is_empty($url)) {
            throw new \RuntimeException("Set empty base url for Payments API.");
        }
        $this->context->setBaseUrl($url);
        return $this;
    }

    /**
     * Get the value of Access Token
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->context->getAccessToken();
    }

    /**
     * Set the value of Access Token
     *
     * @param mixed accessToken
     *
     * @return self
     */
    public function setAccessToken($accessToken)
    {
        $this->context->setAccessToken($accessToken);

        return $this;
    }

    /**
     * Get the value of Refresh Token
     *
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->context->getRefreshToken();
    }

    /**
     * Set the value of Refresh Token
     *
     * @param mixed refreshToken
     *
     * @return self
     */
    public function setRefreshToken($refreshToken)
    {
        $this->context->setRefreshToken($refreshToken);

        return $this;
    }

    /**
     * Set the value of Enviornment
     *
     * @param mixed enviornment
     *
     * @return self
     */
    public function setEnviornment($environment)
    {
        $this->context->setEnviornment($environment);
    }

    /**
     * Get the value of A list of interceptors to be used in the client
     *
     * @return mixed
     */
    public function getAllInterceptors()
    {
        return $this->interceptors;
    }

    public function getInterceptor(string $interceptorName)
    {
        if (array_key_exists($interceptorName, $this->interceptors)) {
            return $this->interceptors[$interceptorName];
        } else {
            return null;
        }
    }

    public function addInterceptor($name, $interceptor)
    {
        $interceptor = $this->getInterceptor($name);
        if (!isset($interceptor)) {
            $this->interceptors[$name] = $interceptor;
        } else {
            throw new \RuntimeException("Interceptor with name: " . $name . " already exists.");
        }
        return $this;
    }

    public function removeInterceptor()
    {
    }

    /**
     * Get the value of Oauth Authenticator
     *
     * @return mixed
     */
    public function getOauth2Authenticator()
    {
        return $this->oauth2Authenticator;
    }

    /**
     * Set the value of Oauth Authenticator
     *
     * @param mixed oauth2Authenticator
     *
     * @return self
     */
    public function setOauth2Authenticator($oauth2Authenticator)
    {
        $this->oauth2Authenticator = $oauth2Authenticator;

        return $this;
    }

    /**
     * Get the value of Http Client
     *
     * @return mixed
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Set the value of Http Client
     *
     * @param mixed httpClient
     *
     * @return self
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Get the value of The Http context for the client.
     *
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set the value of The Http context for the client.
     *
     * @param mixed context
     *
     * @return self
     */
    public function setContext(ClientContext $context)
    {
        $this->context = $context;

        return $this;
    }
}
