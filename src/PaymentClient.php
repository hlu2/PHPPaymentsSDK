<?php
namespace QuickBooksOnline\Payments;

use QuickBooksOnline\Payments\Facade\ChargeBuilder;
use QuickBooksOnline\Payments\Facade\CardBuilder;
use QuickBooksOnline\Payments\Facade\FacadeConverter;
use QuickBooksOnline\Payments\HttpClients\core\ClientFactory;
use QuickBooksOnline\Payments\HttpClients\Response\IntuitResponse;
use QuickBooksOnline\Payments\HttpClients\Response\ResponseInterface;
use QuickBooksOnline\Payments\HttpClients\Response\ResponseFactory;

use QuickBooksOnline\Payments\Module\Charge;
use QuickBooksOnline\Payments\Module\Card;

class PaymentClient
{
    private $accessToken;
    private $refreshToken;
    private $enviornment;
    private $baseUrl;

    /**
     * A list of interceptors to be used in the client
     */
    private $interceptors;


    private $oauth2Authenticator;


    private $httpClient;


    public function __construct()
    {
        $this->httpClient = ClientFactory::buildCurlClient();
    }


    /**
     * Create a Charge
     */
    public function charge(Charge $charge, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = ChargeBuilder::createChargeRequest($charge, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Retrieve a Charge
     */
    public function retrieveCharge(string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = ChargeBuilder::createGetChargeRequest($chargeId, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Capture a Charge
     */
    public function captureCharge(Charge $charge, string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = ChargeBuilder::createCaptureChargeRequest($charge, $chargeId, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Refund a Charge
     */
    public function refundCharge(Charge $charge, string $chargeId, string $requestId = "") : ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = ChargeBuilder::createRefundChargeRequest($charge, $chargeId, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Get a refund by ID.
     */
    public function getRefundDetail($chargeId, $refundId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = ChargeBuilder::refundBy($chargeId, $refundId, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Create a Card
    */
    public function createCard(Card $card, $customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = CardBuilder::createCard($card, $customerID, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Delete a Card
    */
    public function deleteCard($customerID, $cardId, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = CardBuilder::deleteCard($customerID, $cardId, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
    * Get all cards
    */
    public function getAllCardsFor($customerID, string $requestId = ""): ResponseInterface
    {
        if (empty($requestId)) {
            $requestId = $this->generateRequestID();
        }
        $request = CardBuilder::getAllCards($customerID, $requestId, $this);
        $response = $this->httpClient->send($request);
        $this->updateResponseBodyToObj($response);
        return $response;
    }

    /**
     * Auto generate request ID
     */
    private function generateRequestID() : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 20; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    private function updateResponseBodyToObj(&$response)
    {
        if (!$response->failed() && !empty($response->getBody())) {
            $objBody = FacadeConverter::objectFrom($response->getBody(), $response->getAssociatedRequest()->getRequestType());
            $response->setBody($objBody);
        }
    }



    public function getStandardHeaderWithRequestID(string $requestId) : array
    {
        return array(
         'Accept' => 'application/json',
         'Content-Type' => 'application/json',
         'Request-Id' => $requestId,
         'Authorization' => "Bearer " . $this->accessToken
       );
    }

    public function getStandardHeaderWithRequestIDForDelete(string $requestId) : array
    {
        return array(
         'Content-Type' => 'application/json',
         'Request-Id' => $requestId,
         'Authorization' => "Bearer " . $this->accessToken
       );
    }

    public function getNonAuthHeaderWithRequestID() : array
    {
        return array(
         'Accept' => 'application/json',
         'Request-Id' => $requestId,
         'Content-Type' => 'application/json'
       );
    }

    public function getUrl()
    {
        return $this->baseUrl;
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
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * Get the value of Access Token
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
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
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the value of Refresh Token
     *
     * @return mixed
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
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
        $this->refreshToken = $refreshToken;

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
        $env = strtolower($environment);
        if (substr($env, 0, strlen("prod")) === "prod") {
            $this->baseUrl =  Config::PRODUCTION_URL;
        } else {
            $this->baseUrl =  Config::SANDBOX_URL;
        }
        return $this;
    }

    /**
     * Get the value of A list of interceptors to be used in the client
     *
     * @return mixed
     */
    public function getInterceptors()
    {
        return $this->interceptors;
    }

    /**
     * Set the value of A list of interceptors to be used in the client
     *
     * @param mixed interceptors
     *
     * @return self
     */
    public function setInterceptors($interceptors)
    {
        $this->interceptors = $interceptors;

        return $this;
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
}
